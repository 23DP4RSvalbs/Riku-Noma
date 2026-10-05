<?php

namespace App\Http\Controllers;

use App\Models\Kategorija;
use App\Models\Riks;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ToolController extends Controller
{
    private const STATUSES = ['pieejams', 'iznomats', 'apkope', 'bojats', 'arhivets'];

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:kategorija,kategorijaID'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $tools = Riks::with('kategorija')
            ->when(! $this->isAdministrator($request), function ($query) {
                $query->where('redzamsKatalogs', true)->where('statuss', 'pieejams');
            })
            ->when($validated['search'] ?? null, function ($query, string $search) {
                $query->where('nosaukums', 'like', '%' . $search . '%');
            })
            ->when($validated['category_id'] ?? null, function ($query, int $categoryId) {
                $query->where('kategorijaID', $categoryId);
            })
            ->orderByDesc('rikID')
            ->paginate($validated['per_page'] ?? 15);

        return response()->json($tools);
    }

    public function show(Request $request, Riks $rik): JsonResponse
    {
        if (! $this->isAdministrator($request) && ! $this->isPubliclyAvailable($rik)) {
            return response()->json(['message' => 'Rīks nav pieejams katalogā.'], 404);
        }

        return response()->json($rik->load('kategorija'));
    }

    public function availability(Request $request, Riks $rik): JsonResponse
    {
        if (! $this->isAdministrator($request) && ! $this->isPubliclyAvailable($rik)) {
            return response()->json(['message' => 'Rīks nav pieejams katalogā.'], 404);
        }

        $validated = $request->validate([
            'month' => ['sometimes', 'required', 'date_format:Y-m'],
            'from' => ['required_without:month', 'date_format:Y-m-d'],
            'to' => ['required_without:month', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        if (isset($validated['month'])) {
            $monthStart = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $reservations = $rik->pasutijumi()
                ->whereRaw("LOWER(pasutijums.statuss) NOT IN ('atcelts', 'izpildits', 'izpildīts')")
                ->wherePivot('nomassakums', '<=', $monthEnd->toDateString())
                ->wherePivot('nomasbeigums', '>=', $monthStart->toDateString())
                ->get();
            $reservedByDate = [];

            foreach ($reservations as $reservation) {
                $start = Carbon::parse($reservation->pivot->nomassakums);
                $end = Carbon::parse($reservation->pivot->nomasbeigums);
                if ($start->lt($monthStart)) $start = $monthStart->copy();
                if ($end->gt($monthEnd)) $end = $monthEnd->copy();

                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    $day = $date->toDateString();
                    $reservedByDate[$day] = ($reservedByDate[$day] ?? 0) + (int) $reservation->pivot->daudzums_pozicija;
                }
            }

            $days = [];
            for ($date = $monthStart->copy(); $date->lte($monthEnd); $date->addDay()) {
                $day = $date->toDateString();
                $reserved = $reservedByDate[$day] ?? 0;
                $days[$day] = [
                    'reserved_quantity' => $reserved,
                    'available_quantity' => max(0, $rik->daudzums - $reserved),
                ];
            }

            return response()->json([
                'tool_id' => $rik->getKey(),
                'month' => $validated['month'],
                'total_quantity' => $rik->daudzums,
                'days' => $days,
            ]);
        }

        $reservedQuantity = $rik->pasutijumi()
            ->whereRaw("LOWER(pasutijums.statuss) NOT IN ('atcelts', 'izpildits', 'izpildīts')")
            ->wherePivot('nomassakums', '<=', $validated['to'])
            ->wherePivot('nomasbeigums', '>=', $validated['from'])
            ->sum('pasutijuma_riks.daudzums_pozicija');

        return response()->json([
            'tool_id' => $rik->rikID,
            'from' => $validated['from'],
            'to' => $validated['to'],
            'total_quantity' => $rik->daudzums,
            'reserved_quantity' => (int) $reservedQuantity,
            'available_quantity' => max(0, $rik->daudzums - $reservedQuantity),
        ]);
    }

    public function categories(): JsonResponse
    {
        return response()->json(Kategorija::orderBy('nosaukums')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateTool($request);
        $validated['foto'] = $this->storePhoto($request->file('foto'));

        return response()->json(Riks::create($validated)->load('kategorija'), 201);
    }

    public function update(Request $request, Riks $rik): JsonResponse
    {
        $validated = $this->validateTool($request, true);

        if ($request->hasFile('foto')) {
            $this->deletePhoto($rik->foto);
            $validated['foto'] = $this->storePhoto($request->file('foto'));
        }

        $rik->update($validated);

        return response()->json($rik->fresh()->load('kategorija'));
    }

    public function destroy(Request $request, Riks $rik): JsonResponse
    {
        $mode = $request->input('dzeshanas_modelis', $request->input('modelis', 'arhivet'));
        $request->merge(['dzeshanas_modelis' => $mode]);
        $request->validate(['dzeshanas_modelis' => ['required', Rule::in(['dzest', 'arhivet'])]]);

        if ($mode === 'arhivet') {
            $rik->update(['redzamsKatalogs' => false, 'statuss' => 'arhivets']);

            return response()->json(['message' => 'Rīks arhivēts.', 'tool' => $rik->fresh()]);
        }

        $this->deletePhoto($rik->foto);
        $rik->delete();

        return response()->json(['message' => 'Rīks dzēsts.']);
    }

    private function validateTool(Request $request, bool $partial = false): array
    {
        $required = $partial ? ['sometimes'] : ['required'];

        return $request->validate([
            'nosaukums' => [...$required, 'string', 'max:100'],
            'apraksts' => ['sometimes', 'nullable', 'string'],
            'cenadiena' => [...$required, 'numeric', 'decimal:0,2', 'min:0'],
            'daudzums' => [...$required, 'integer', 'min:0'],
            'kategorijaID' => [...$required, 'integer', 'exists:kategorija,kategorijaID'],
            'statuss' => [...$required, 'string', Rule::in(self::STATUSES)],
            'kods' => ['sometimes', 'nullable', 'string', 'max:50'],
            'zimols' => ['sometimes', 'nullable', 'string', 'max:100'],
            'nomasilgumsmin' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'nomasilgumsmax' => ['sometimes', 'nullable', 'integer', 'min:1', 'gte:nomasilgumsmin'],
            'redzamsKatalogs' => ['sometimes', 'boolean'],
            'foto' => [$partial ? 'sometimes' : 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'cenadiena.min' => 'Dienas cenai jābūt vismaz 0.',
            'daudzums.min' => 'Daudzumam jābūt vismaz 0.',
        ], [
            'cenadiena' => 'cena dienā',
            'daudzums' => 'daudzums',
        ]);
    }

    private function storePhoto(?UploadedFile $photo): ?string
    {
        return $photo?->store('tools', 'public');
    }

    private function deletePhoto(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function isAdministrator(Request $request): bool
    {
        return $request->user()?->lomas()->where('nosaukums', 'Administrators')->exists() ?? false;
    }

    private function isPubliclyAvailable(Riks $rik): bool
    {
        return $rik->redzamsKatalogs && $rik->statuss === 'pieejams';
    }
}
