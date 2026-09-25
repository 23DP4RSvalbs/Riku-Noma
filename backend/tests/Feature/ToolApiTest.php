<?php

namespace Tests\Feature;

use App\Models\Kategorija;
use App\Models\Lietotajs;
use App\Models\Loma;
use App\Models\Pasutijums;
use App\Models\Riks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ToolApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_list_only_publicly_available_tools(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Urbji']);
        Riks::create($this->toolData($category, 'Pieejams urbis'));
        Riks::create($this->toolData($category, 'Slēpts urbis', false));
        Riks::create($this->toolData($category, 'Apkopē esošs urbis', true, 'apkope'));

        $this->getJson('/api/categories')->assertOk()->assertJsonFragment(['nosaukums' => 'Urbji']);
        $this->getJson('/api/tools')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['nosaukums' => 'Pieejams urbis'])
            ->assertJsonMissing(['nosaukums' => 'Slēpts urbis'])
            ->assertJsonMissing(['nosaukums' => 'Apkopē esošs urbis']);
    }

    public function test_public_catalog_supports_search_category_and_pagination(): void
    {
        $drills = Kategorija::create(['nosaukums' => 'Urbji']);
        $saws = Kategorija::create(['nosaukums' => 'Zāģi']);
        Riks::create($this->toolData($drills, 'Akumulatora urbis'));
        Riks::create($this->toolData($drills, 'Triecienurbjmašīna'));
        Riks::create($this->toolData($saws, 'Ripzāģis'));

        $this->getJson('/api/tools?search=urbj&category_id=' . $drills->getKey() . '&per_page=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('meta.per_page', 1);
    }

    public function test_public_details_and_availability_hide_non_public_tools(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Urbji']);
        $tool = Riks::create($this->toolData($category, 'Pieejams urbis'));
        $hidden = Riks::create($this->toolData($category, 'Slēpts urbis', false));

        $this->getJson('/api/tools/' . $tool->getKey())
            ->assertOk()
            ->assertJsonPath('nosaukums', 'Pieejams urbis');
        $this->getJson('/api/tools/' . $hidden->getKey())->assertNotFound();
        $this->getJson('/api/tools/' . $hidden->getKey() . '/availability?from=2026-10-01&to=2026-10-03')
            ->assertNotFound();
    }

    public function test_availability_counts_only_overlapping_active_orders(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Urbji']);
        $tool = Riks::create($this->toolData($category, 'Pieejams urbis'));
        $user = $this->userWithRole('Klients', 'renter@example.com');

        $activeOrder = Pasutijums::create(['lietotajID' => $user->getKey(), 'statuss' => 'Jauns']);
        $activeOrder->riki()->attach($tool->getKey(), [
            'daudzums_pozicija' => 2,
            'nomassakums' => '2026-10-02',
            'nomasbeigums' => '2026-10-04',
        ]);
        $cancelledOrder = Pasutijums::create(['lietotajID' => $user->getKey(), 'statuss' => 'Atcelts']);
        $cancelledOrder->riki()->attach($tool->getKey(), [
            'daudzums_pozicija' => 2,
            'nomassakums' => '2026-10-02',
            'nomasbeigums' => '2026-10-04',
        ]);

        $this->getJson('/api/tools/' . $tool->getKey() . '/availability?from=2026-10-03&to=2026-10-03')
            ->assertOk()
            ->assertJsonPath('reserved_quantity', 2)
            ->assertJsonPath('available_quantity', 3);
    }

    public function test_non_admin_cannot_create_tool(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Darbnīca']);
        $user = $this->userWithRole('Klients', 'client@example.com');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/tools', $this->toolData($category))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_archive_tool_with_photo(): void
    {
        Storage::fake('public');
        $category = Kategorija::create(['nosaukums' => 'Darbnīca']);
        $admin = $this->userWithRole('Administrators', 'admin@example.com');

        $response = $this->actingAs($admin, 'sanctum')->post('/api/tools', [
            ...$this->toolData($category),
            'foto' => UploadedFile::fake()->image('urbis.png'),
        ]);

        $response->assertCreated()->assertJsonPath('statuss', 'pieejams');
        $tool = Riks::firstOrFail();
        Storage::disk('public')->assertExists($tool->foto);

        $this->actingAs($admin, 'sanctum')
            ->patchJson('/api/tools/' . $tool->getKey(), ['daudzums' => 0, 'statuss' => 'apkope'])
            ->assertOk()
            ->assertJsonPath('statuss', 'apkope');

        $this->actingAs($admin, 'sanctum')
            ->deleteJson('/api/tools/' . $tool->getKey(), ['dzeshanas_modelis' => 'arhivet'])
            ->assertOk();

        $this->assertDatabaseHas('riks', [
            'rikID' => $tool->getKey(),
            'redzamsKatalogs' => false,
            'statuss' => 'arhivets',
        ]);
    }

    public function test_invalid_tool_values_are_rejected(): void
    {
        $admin = $this->userWithRole('Administrators', 'validation@example.com');
        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/tools', [
                'nosaukums' => str_repeat('a', 101),
                'cenadiena' => -1,
                'daudzums' => -1,
                'kategorijaID' => 999,
                'statuss' => 'nezinams',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nosaukums', 'cenadiena', 'daudzums', 'kategorijaID', 'statuss']);
    }

    private function userWithRole(string $role, string $email): Lietotajs
    {
        $user = Lietotajs::create([
            'vards' => 'Testa lietotājs',
            'epasts' => $email,
            'parole' => Hash::make('drosha123'),
        ]);
        $user->lomas()->attach(Loma::create(['nosaukums' => $role])->getKey());

        return $user;
    }

    private function toolData(Kategorija $category, string $name = 'Akumulatora urbis', bool $visible = true, string $status = 'pieejams'): array
    {
        return [
            'nosaukums' => $name,
            'apraksts' => 'Testa rīks',
            'cenadiena' => '12.50',
            'daudzums' => 5,
            'kategorijaID' => $category->getKey(),
            'statuss' => $status,
            'redzamsKatalogs' => $visible,
        ];
    }
}
