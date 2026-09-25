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
use Tests\TestCase;

class ToolApiTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdminUser(): Lietotajs
    {
        Loma::firstOrCreate(['nosaukums' => 'Administrators']);

        $user = Lietotajs::create([
            'vards' => 'Admins',
            'epasts' => 'admin@example.com',
            'parole' => Hash::make('drosha123'),
        ]);

        $user->lomas()->attach(Loma::where('nosaukums', 'Administrators')->value('lomasID'));

        return $user;
    }

    public function test_guest_can_list_visible_tools_and_categories(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Urbji', 'apraksts' => 'Test']);
        Riks::create([
            'nosaukums' => 'Urbjmašīna',
            'cenadiena' => 15.50,
            'daudzums' => 4,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'pieejams',
            'redzamsKatalogs' => true,
        ]);
        Riks::create([
            'nosaukums' => 'Slēgts rīks',
            'cenadiena' => 10,
            'daudzums' => 1,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'slēgts',
            'redzamsKatalogs' => false,
        ]);

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonFragment(['nosaukums' => 'Urbji']);

        $this->getJson('/api/tools')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['nosaukums' => 'Urbjmašīna'])
            ->assertJsonMissing(['nosaukums' => 'Slēgts rīks']);
    }

    public function test_public_catalog_supports_search_category_and_pagination(): void
    {
        $drills = Kategorija::create(['nosaukums' => 'Urbji']);
        $saws = Kategorija::create(['nosaukums' => 'Zāģi']);

        foreach (['Akumulatora urbis', 'Triecienurbjmašīna', 'Ripzāģis'] as $name) {
            Riks::create([
                'nosaukums' => $name,
                'cenadiena' => 10,
                'daudzums' => 2,
                'kategorijaID' => str_contains($name, 'zāģ') ? $saws->kategorijaID : $drills->kategorijaID,
                'statuss' => 'pieejams',
                'redzamsKatalogs' => true,
            ]);
        }

        $this->getJson('/api/tools?search=urbj&category_id=' . $drills->kategorijaID . '&per_page=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('meta.per_page', 1);
    }

    public function test_public_tool_details_and_availability_hide_non_public_tools(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Urbji']);
        $tool = Riks::create([
            'nosaukums' => 'Pieejams urbis',
            'cenadiena' => 10,
            'daudzums' => 5,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'pieejams',
            'redzamsKatalogs' => true,
        ]);
        $hidden = Riks::create([
            'nosaukums' => 'Slēpts urbis',
            'cenadiena' => 10,
            'daudzums' => 5,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'pieejams',
            'redzamsKatalogs' => false,
        ]);

        $this->getJson('/api/tools/' . $tool->rikID)
            ->assertOk()
            ->assertJsonPath('nosaukums', 'Pieejams urbis');
        $this->getJson('/api/tools/' . $hidden->rikID)->assertNotFound();
        $this->getJson('/api/tools/' . $hidden->rikID . '/availability?from=2026-10-01&to=2026-10-03')
            ->assertNotFound();
    }

    public function test_availability_counts_only_overlapping_active_orders(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Urbji']);
        $tool = Riks::create([
            'nosaukums' => 'Pieejams urbis',
            'cenadiena' => 10,
            'daudzums' => 5,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'pieejams',
            'redzamsKatalogs' => true,
        ]);
        $user = Lietotajs::create([
            'vards' => 'Nomnieks',
            'epasts' => 'renter@example.com',
            'parole' => Hash::make('drosha123'),
        ]);

        $activeOrder = Pasutijums::create(['lietotajID' => $user->lietotajsID, 'statuss' => 'gaida']);
        $activeOrder->riki()->attach($tool->rikID, [
            'daudzums_pozicija' => 2,
            'nomassakums' => '2026-10-02',
            'nomasbeigums' => '2026-10-04',
        ]);
        $completedOrder = Pasutijums::create(['lietotajID' => $user->lietotajsID, 'statuss' => 'izpildits']);
        $completedOrder->riki()->attach($tool->rikID, [
            'daudzums_pozicija' => 2,
            'nomassakums' => '2026-10-02',
            'nomasbeigums' => '2026-10-04',
        ]);

        $this->getJson('/api/tools/' . $tool->rikID . '/availability?from=2026-10-03&to=2026-10-03')
            ->assertOk()
            ->assertJsonPath('reserved_quantity', 2)
            ->assertJsonPath('available_quantity', 3);
    }

    public function test_admin_can_create_update_and_archive_or_delete_tool(): void
    {
        $admin = $this->createAdminUser();
        $category = Kategorija::create(['nosaukums' => 'Mērinstrumenti', 'apraksts' => 'Mērīšana']);

        $create = $this->actingAs($admin, 'sanctum')->postJson('/api/tools', [
            'nosaukums' => 'Lāzera līmenis',
            'cenadiena' => '12.99',
            'daudzums' => 3,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'pieejams',
            'foto' => UploadedFile::fake()->image('laser.png', 300, 300),
        ]);

        $create->assertCreated()
            ->assertJsonPath('tool.nosaukums', 'Lāzera līmenis')
            ->assertJsonPath('tool.statuss', 'pieejams');

        $tool = Riks::first();

        $this->actingAs($admin, 'sanctum')
            ->patchJson('/api/tools/' . $tool->rikID, [
                'nosaukums' => 'Jaunais līmenis',
                'cenadiena' => 19.99,
                'daudzums' => 5,
                'kategorijaID' => $category->kategorijaID,
                'statuss' => 'aizņemts',
            ])
            ->assertOk()
            ->assertJsonPath('tool.nosaukums', 'Jaunais līmenis')
            ->assertJsonPath('tool.statuss', 'aizņemts');

        $this->actingAs($admin, 'sanctum')
            ->deleteJson('/api/tools/' . $tool->rikID . '?mode=archive')
            ->assertOk()
            ->assertJsonPath('message', 'Rīks arhivēts.');

        $this->assertDatabaseHas('riks', ['rikID' => $tool->rikID, 'redzamsKatalogs' => false]);

        $archivedTool = Riks::find($tool->rikID);
        $this->actingAs($admin, 'sanctum')
            ->deleteJson('/api/tools/' . $archivedTool->rikID . '?mode=delete')
            ->assertOk();

        $this->assertDatabaseMissing('riks', ['rikID' => $tool->rikID]);
    }

    public function test_non_admin_is_forbidden_from_modifying_tools(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Dārza rīki', 'apraksts' => 'Dārzs']);
        $user = Lietotajs::create([
            'vards' => 'Klients',
            'epasts' => 'client@example.com',
            'parole' => Hash::make('drosha123'),
        ]);
        $user->lomas()->attach(Loma::firstOrCreate(['nosaukums' => 'Klients'])->lomasID);

        $this->actingAs($user, 'sanctum')->postJson('/api/tools', [
            'nosaukums' => 'Neatļauts',
            'cenadiena' => 10,
            'daudzums' => 1,
            'kategorijaID' => $category->kategorijaID,
            'statuss' => 'pieejams',
        ])->assertForbidden();
    }

    public function test_tool_validation_rejects_invalid_values(): void
    {
        $admin = $this->createAdminUser();
        $category = Kategorija::create(['nosaukums' => 'Urbji', 'apraksts' => 'Test']);

        $this->actingAs($admin, 'sanctum')->postJson('/api/tools', [
            'nosaukums' => str_repeat('a', 101),
            'cenadiena' => -1,
            'daudzums' => -1,
            'kategorijaID' => 999,
            'statuss' => 'nepareizs',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['nosaukums', 'cenadiena', 'daudzums', 'kategorijaID', 'statuss']);
    }
}
