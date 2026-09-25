<?php

namespace App\Http\Controllers;

use App\Models\Lietotajs;
use App\Models\Loma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        if (! $request->has('parole_confirmation') && $request->has('paroles_atkartojums')) {
            $request->merge(['parole_confirmation' => $request->input('paroles_atkartojums')]);
        }

        $validated = $request->validate([
            'vards' => ['required', 'string', 'max:100'],
            'epasts' => ['required', 'email', 'max:100', 'unique:lietotajs,epasts'],
            'parole' => ['required', 'string', 'min:8', 'regex:/[A-Za-z]/', 'regex:/[0-9]/'],
            'parole_confirmation' => ['required', 'same:parole'],
            'telefons' => ['nullable', 'string', 'max:20'],
        ], [
            'required' => 'Lauks :attribute ir obligāts.',
            'vards.max' => 'Vārds nedrīkst pārsniegt 100 rakstzīmes.',
            'epasts.email' => 'Ievadiet derīgu e-pasta adresi.',
            'epasts.max' => 'E-pasts nedrīkst pārsniegt 100 rakstzīmes.',
            'epasts.unique' => 'Šis e-pasts jau ir reģistrēts.',
            'parole.min' => 'Parolei jābūt vismaz 8 rakstzīmes garai.',
            'parole.regex' => 'Parolei jāsatur burti un cipari.',
            'parole_confirmation.same' => 'Atkārtotā parole nesakrīt.',
        ], [
            'vards' => 'vārds',
            'epasts' => 'e-pasts',
            'parole' => 'parole',
            'parole_confirmation' => 'atkārtotā parole',
            'telefons' => 'tālrunis',
        ]);

        $user = DB::transaction(function () use ($validated): Lietotajs {
            $user = Lietotajs::create([
                'vards' => $validated['vards'],
                'epasts' => $validated['epasts'],
                'parole' => Hash::make($validated['parole']),
                'telefons' => $validated['telefons'] ?? null,
            ]);

            $clientRole = Loma::firstOrCreate(['nosaukums' => 'Klients']);
            $user->lomas()->attach($clientRole->getKey());

            return $user->load('lomas');
        });

        return response()->json([
            'message' => 'Lietotājs veiksmīgi reģistrēts.',
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'epasts' => ['required', 'email'],
            'parole' => ['required', 'string'],
        ], [
            'required' => 'Lauks :attribute ir obligāts.',
            'email' => 'Ievadiet derīgu e-pasta adresi.',
        ], [
            'epasts' => 'e-pasts',
            'parole' => 'parole',
        ]);

        $user = Lietotajs::where('epasts', $credentials['epasts'])->first();

        if (! $user || ! Hash::check($credentials['parole'], $user->parole)) {
            return response()->json([
                'message' => 'Nepareizs e-pasts vai parole.',
            ], 401);
        }

        return response()->json([
            'message' => 'Veiksmīga pieslēgšanās.',
            'user' => $user->load('lomas'),
            'token' => $user->createToken('auth-token')->plainTextToken,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Veiksmīgi atvienojāties.']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('lomas'));
    }
}
