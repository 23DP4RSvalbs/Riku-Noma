<?php

namespace Tests\Feature;

use App\Models\Kategorija;
use App\Models\Lietotajs;
use App\Models\Loma;
use App\Models\Pasutijums;
use App\Models\Riks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order_with_total_and_dates(): void
    {
        $user = $this->userWithRole('Klients', 'user@example.com');
        $tool = $this->tool(3, '10.50');

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/orders', [
            'riki' => [[
                'rikID' => $tool->getKey(),
                'daudzums' => 2,
                'nomasSakums' => '25.09.2026',
                'nomasBeigums' => '27.09.2026',
            ]],
        ]);

        $response->assertCreated()->assertJsonPath('statuss', 'Jauns');
        $this->assertDatabaseHas('pasutijums', ['kopsumma' => 63.00, 'lietotajID' => $user->getKey()]);
        $this->assertDatabaseHas('pasutijuma_riks', [
            'rikID' => $tool->getKey(),
            'daudzums_pozicija' => 2,
            'nomassakums' => '2026-09-25',
            'nomasbeigums' => '2026-09-27',
        ]);
    }

    public function test_overlapping_order_is_rejected_when_quantity_is_unavailable(): void
    {
        $firstUser = $this->userWithRole('Klients', 'first@example.com');
        $secondUser = $this->userWithRole('Klients', 'second@example.com');
        $tool = $this->tool(1, '10.00');
        $payload = $this->orderPayload($tool);

        $this->actingAs($firstUser, 'sanctum')->postJson('/api/orders', $payload)->assertCreated();
        $this->actingAs($secondUser, 'sanctum')
            ->postJson('/api/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Šis instruments jau ir aizņemts šajos datumos');
    }

    public function test_user_can_only_see_and_cancel_own_new_orders(): void
    {
        $owner = $this->userWithRole('Klients', 'owner@example.com');
        $other = $this->userWithRole('Klients', 'other@example.com');
        $tool = $this->tool(1, '10.00');

        $order = Pasutijums::create([
            'kopsumma' => 10,
            'statuss' => 'Jauns',
            'lietotajID' => $owner->getKey(),
        ]);
        $order->riki()->attach($tool->getKey(), [
            'daudzums_pozicija' => 1,
            'nomassakums' => '2026-09-25',
            'nomasbeigums' => '2026-09-25',
        ]);

        $this->actingAs($other, 'sanctum')->getJson('/api/my-orders')->assertOk()->assertJsonCount(0);
        $this->actingAs($owner, 'sanctum')->postJson('/api/orders/'.$order->getKey().'/cancel')
            ->assertOk()
            ->assertJsonPath('statuss', 'Atcelts');
    }

    public function test_admin_can_filter_orders_and_update_status(): void
    {
        $admin = $this->userWithRole('Administrators', 'admin@example.com');
        $user = $this->userWithRole('Klients', 'customer@example.com');
        $order = Pasutijums::create([
            'kopsumma' => 10,
            'statuss' => 'Jauns',
            'lietotajID' => $user->getKey(),
        ]);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/orders?statuss=Jauns&datums_no=25.09.2026')
            ->assertOk()
            ->assertJsonCount(1);

        $this->actingAs($admin, 'sanctum')
            ->patchJson('/api/orders/'.$order->getKey().'/status', ['statuss' => 'Apstiprinats'])
            ->assertOk()
            ->assertJsonPath('statuss', 'Apstiprinats');
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

    private function tool(int $quantity, string $price): Riks
    {
        $category = Kategorija::create(['nosaukums' => uniqid('Kategorija')]);

        return Riks::create([
            'nosaukums' => 'Akumulatora urbis',
            'cenadiena' => $price,
            'daudzums' => $quantity,
            'kategorijaID' => $category->getKey(),
            'statuss' => 'pieejams',
        ]);
    }

    private function orderPayload(Riks $tool): array
    {
        return [
            'riki' => [[
                'rikID' => $tool->getKey(),
                'daudzums' => 1,
                'nomasSakums' => '25.09.2026',
                'nomasBeigums' => '27.09.2026',
            ]],
        ];
    }
}