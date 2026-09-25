<?php

namespace Tests\Feature;

use App\Models\Lietotajs;
use App\Models\Loma;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_client_role_and_token(): void
    {
        Loma::create(['nosaukums' => 'Klients']);

        $response = $this->postJson('/api/register', [
            'vards' => 'Anna Kalniņa',
            'epasts' => 'anna@example.com',
            'parole' => 'drosha123',
            'parole_confirmation' => 'drosha123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.epasts', 'anna@example.com')
            ->assertJsonPath('user.lomas.0.nosaukums', 'Klients')
            ->assertJsonStructure(['token', 'user']);

        $this->assertDatabaseHas('lietotajs', ['epasts' => 'anna@example.com']);
        $this->assertDatabaseHas('lietotajloma', [
            'lietotajID' => Lietotajs::where('epasts', 'anna@example.com')->value('lietotajsID'),
            'lomasID' => Loma::where('nosaukums', 'Klients')->value('lomasID'),
        ]);
    }

    public function test_registration_validates_password_and_confirmation_in_latvian(): void
    {
        $response = $this->postJson('/api/register', [
            'vards' => str_repeat('a', 101),
            'epasts' => 'anna@example.com',
            'parole' => 'short',
            'parole_confirmation' => 'different',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['vards', 'parole', 'parole_confirmation']);
    }

    public function test_login_rejects_invalid_credentials_in_latvian(): void
    {
        $user = Lietotajs::create([
            'vards' => 'Anna Kalniņa',
            'epasts' => 'anna@example.com',
            'parole' => Hash::make('drosha123'),
        ]);

        $response = $this->postJson('/api/login', [
            'epasts' => $user->epasts,
            'parole' => 'nepareizi1',
        ]);

        $response->assertUnauthorized()
            ->assertJsonPath('message', 'Nepareizs e-pasts vai parole.');
    }

    public function test_user_can_login_and_read_profile(): void
    {
        $user = Lietotajs::create([
            'vards' => 'Anna Kalniņa',
            'epasts' => 'anna@example.com',
            'parole' => Hash::make('drosha123'),
        ]);

        $login = $this->postJson('/api/login', [
            'epasts' => $user->epasts,
            'parole' => 'drosha123',
        ]);

        $login->assertOk()
            ->assertJsonPath('user.epasts', 'anna@example.com')
            ->assertJsonStructure(['token', 'user']);

        $this->withToken($login->json('token'))
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('epasts', 'anna@example.com');
    }

    public function test_user_can_logout_and_token_is_revoked(): void
    {
        $user = Lietotajs::create([
            'vards' => 'Anna Kalniņa',
            'epasts' => 'anna@example.com',
            'parole' => Hash::make('drosha123'),
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')->assertOk();
        $this->withToken($token)->getJson('/api/user')->assertUnauthorized();
    }

    public function test_role_admin_middleware_accepts_administrator_role(): void
    {
        Route::middleware(['auth:sanctum', 'role:admin'])
            ->get('/api/admin-test', fn () => response()->json(['ok' => true]));

        $adminRole = Loma::create(['nosaukums' => 'Administrators']);
        $user = Lietotajs::create([
            'vards' => 'Admin',
            'epasts' => 'admin@example.com',
            'parole' => Hash::make('drosha123'),
        ]);
        $user->lomas()->attach($adminRole->getKey());

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/admin-test')
            ->assertOk();
    }
}
