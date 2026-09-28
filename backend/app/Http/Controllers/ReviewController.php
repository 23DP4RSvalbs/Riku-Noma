<?php

namespace App\Http\Controllers;

use App\Models\Lietotajs;
use App\Models\RikAtsauksme;
use App\Models\Riks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Riks $rik): JsonResponse
    {
        $reviews = $rik->atsauksmes()
            ->with('lietotajs:lietotajsID,vards')
            ->latest()
            ->limit(50)
            ->get();

        return response()->json([
            'average_rating' => round((float) $rik->atsauksmes()->avg('vertejums'), 1),
            'review_count' => $rik->atsauksmes()->count(),
            'reviews' => $reviews,
        ]);
    }

    public function eligibility(Request $request, Riks $rik): JsonResponse
    {
        $user = $request->user();
        $completedRental = $user->pasutijumi()
            ->where('statuss', 'Izpildits')
            ->whereHas('riki', fn ($query) => $query->where('riks.rikID', $rik->getKey()))
            ->exists();
        $hasReviewed = $rik->atsauksmes()->where('lietotajID', $user->getKey())->exists();

        return response()->json([
            'has_completed_rental' => $completedRental,
            'has_reviewed' => $hasReviewed,
            'can_review' => $completedRental && ! $hasReviewed,
        ]);
    }

    public function store(Request $request, Riks $rik): JsonResponse
    {
        $validated = $request->validate([
            'vertejums' => ['required', 'integer', 'between:1,5'],
            'teksts' => ['required', 'string', 'min:3', 'max:1000'],
        ], [
            'vertejums.between' => 'Vērtējumam jābūt no 1 līdz 5 zvaigznēm.',
            'teksts.min' => 'Atsauksmei jābūt vismaz 3 rakstzīmes garai.',
            'teksts.max' => 'Atsauksme nedrīkst pārsniegt 1000 rakstzīmes.',
        ]);

        return DB::transaction(function () use ($request, $rik, $validated): JsonResponse {
            $user = Lietotajs::query()->lockForUpdate()->findOrFail($request->user()->getKey());
            $completedRental = $user->pasutijumi()
                ->where('statuss', 'Izpildits')
                ->whereHas('riki', fn ($query) => $query->where('riks.rikID', $rik->getKey()))
                ->exists();

            if (! $completedRental) {
                return response()->json(['message' => 'Atsauksmi var pievienot pēc pabeigtas šī rīka nomas.'], 403);
            }

            if ($rik->atsauksmes()->where('lietotajID', $user->getKey())->exists()) {
                return response()->json(['message' => 'Par šo rīku atsauksme jau ir pievienota.'], 409);
            }

            $review = RikAtsauksme::create([
                'rikID' => $rik->getKey(),
                'lietotajID' => $user->getKey(),
                'vertejums' => $validated['vertejums'],
                'teksts' => $validated['teksts'],
            ]);

            return response()->json($review->load('lietotajs:lietotajsID,vards'), 201);
        });
    }
}