<?php

namespace Tests\Feature;

use App\Models\Kategorija;
use App\Models\Lietotajs;
use App\Models\Loma;
use App\Models\Riks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ToolApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_read_tools_and_categories_but_cannot_write(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Darbnīca']);
        Riks::create($this->toolData($category));

        $this->getJson('/api/tools')->assertOk()->assertJsonCount(1);
        $this->getJson('/api/categories')->assertOk()->assertJsonCount(1);
        $this->postJson('/api/tools', $this->toolData($category))->assertUnauthorized();
    }

    public function test_non_admin_cannot_create_tool(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Darbnīca']);
        $user = $this->userWithRole('Klients');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/tools', $this->toolData($category))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_archive_tool_with_photo(): void
    {
        Storage::fake('public');
        $category = Kategorija::create(['nosaukums' => 'Darbnīca']);
        $admin = $this->userWithRole('Administrators');

        $response = $this->actingAs($admin, 'sanctum')->post('/api/tools', [
            ...$this->toolData($category),
            'foto' => UploadedFile::fake()->createWithContent(
                'urbis.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
            ),
        ]);

        $response->assertCreated()->assertJsonPath('statuss', 'pieejams');
        $tool = Riks::firstOrFail();
        Storage::disk('public')->assertExists($tool->foto);

        $this->actingAs($admin, 'sanctum')
            ->patchJson('/api/tools/'.$tool->getKey(), [
                'daudzums' => 0,
                'statuss' => 'apkope',
            ])
            ->assertOk()
            ->assertJsonPath('daudzums', 0);

        $this->actingAs($admin, 'sanctum')
            ->deleteJson('/api/tools/'.$tool->getKey(), ['dzeshanas_modelis' => 'arhivet'])
            ->assertOk();

        $this->assertDatabaseHas('riks', [
            'rikID' => $tool->getKey(),
            'redzamsKatalogs' => 0,
            'statuss' => 'arhivets',
        ]);
    }

    public function test_tool_validation_rejects_invalid_values(): void
    {
        $category = Kategorija::create(['nosaukums' => 'Darbnīca']);
        $admin = $this->userWithRole('Administrators');

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/tools', [
                'nosaukums' => str_repeat('a', 101),
                'cenadiena' => -1,
                'daudzums' => -1,
                'kategorijaID' => $category->getKey(),
                'statuss' => 'nezinams',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nosaukums', 'cenadiena', 'daudzums', 'statuss']);
    }

    private function userWithRole(string $role): Lietotajs
    {
        $user = Lietotajs::create([
            'vards' => 'Testa lietotājs',
            'epasts' => strtolower($role).'@example.com',
            'parole' => Hash::make('drosha123'),
        ]);
        $user->lomas()->attach(Loma::create(['nosaukums' => $role])->getKey());

        return $user;
    }

    private function toolData(Kategorija $category): array
    {
        return [
            'nosaukums' => 'Akumulatora urbis',
            'apraksts' => 'Testa rīks',
            'cenadiena' => '12.50',
            'daudzums' => 3,
            'kategorijaID' => $category->getKey(),
            'statuss' => 'pieejams',
        ];
    }
}