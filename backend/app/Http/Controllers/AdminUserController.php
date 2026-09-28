<?php

namespace App\Http\Controllers;

use App\Models\Lietotajs;
use App\Models\Loma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $users = Lietotajs::query()
            ->with('lomas')
            ->withCount('pasutijumi')
            ->when($validated['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('vards', 'like', '%' . $search . '%')
                        ->orWhere('epasts', 'like', '%' . $search . '%')
                        ->orWhere('telefons', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('lietotajsID')
            ->get();

        return response()->json($users);
    }

    public function updateRole(Request $request, Lietotajs $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['Klients', 'Administrators'])],
        ]);

        if ($user->is($request->user())) {
            return response()->json(['message' => 'Jūs nevarat mainīt savu piekļuves līmeni.'], 422);
        }

        return DB::transaction(function () use ($validated, $user): JsonResponse {
            $lockedUser = Lietotajs::query()->lockForUpdate()->findOrFail($user->getKey());
            $currentIsAdmin = $lockedUser->lomas()->where('nosaukums', 'Administrators')->exists();

            if ($currentIsAdmin && $validated['role'] === 'Klients') {
                $administratorIds = Lietotajs::query()
                    ->whereHas('lomas', fn ($query) => $query->where('nosaukums', 'Administrators'))
                    ->orderBy('lietotajsID')
                    ->lockForUpdate()
                    ->pluck('lietotajsID');

                if ($administratorIds->count() <= 1) {
                    return response()->json([
                        'message' => 'Sistēmā jāpaliek vismaz vienam administratoram.',
                    ], 422);
                }
            }

            $role = Loma::firstOrCreate(['nosaukums' => $validated['role']]);
            $lockedUser->lomas()->sync([$role->getKey()]);

            return response()->json($lockedUser->fresh()->load('lomas')->loadCount('pasutijumi'));
        });
    }
}