<?php

namespace App\Http\Controllers;

use App\Models\Riks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ToolController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:kategorija,kategorijaID'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $tools = Riks::with('kategorija')
            ->where('redzamsKatalogs', true)
            ->where('statuss', 'pieejams')
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

    public function show(Riks $tool): JsonResponse
    {
        abort_unless($this->isPubliclyAvailable($tool), 404);

        return response()->json($tool->load('kategorija'));
    }

    public function availability(Request $request, Riks $tool): JsonResponse
    {
        abort_unless($this->isPubliclyAvailable($tool), 404);

        $validated = $request->validate([
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $reservedQuantity = $tool->pasutijumi()
            ->whereRaw("LOWER(pasutijums.statuss) NOT IN ('atcelts', 'izpildits', 'izpildīts')")
            ->wherePivot('nomassakums', '<=', $validated['to'])
            ->wherePivot('nomasbeigums', '>=', $validated['from'])
            ->sum('pasutijuma_riks.daudzums_pozicija');

        $availableQuantity = max(0, $tool->daudzums - $reservedQuantity);

        return response()->json([
            'tool_id' => $tool->rikID,
            'from' => $validated['from'],
            'to' => $validated['to'],
            'total_quantity' => $tool->daudzums,
            'reserved_quantity' => (int) $reservedQuantity,
            'available_quantity' => $availableQuantity,
        ]);
    }

    protected function isPubliclyAvailable(Riks $tool): bool
    {
        return $tool->redzamsKatalogs && $tool->statuss === 'pieejams';
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateTool($request, true);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->storePhoto($request->file('foto'));
        }

        $tool = Riks::create($validated);

        return response()->json([
            'message' => 'Rīks veiksmīgi izveidots.',
            'tool' => $tool->load('kategorija'),
        ], 201);
    }

    public function update(Request $request, Riks $tool): JsonResponse
    {
        $validated = $this->validateTool($request, false, $tool);

        if ($request->hasFile('foto')) {
            $this->deletePhotoIfExists($tool->foto);
            $validated['foto'] = $this->storePhoto($request->file('foto'));
        }

        $tool->update($validated);

        return response()->json([
            'message' => 'Rīks veiksmīgi atjaunināts.',
            'tool' => $tool->fresh()->load('kategorija'),
        ]);
    }

    public function destroy(Request $request, Riks $tool): JsonResponse
    {
        $mode = strtolower($request->query('mode', 'delete'));

        if ($mode === 'archive') {
            $tool->update([
                'redzamsKatalogs' => false,
                'statuss' => 'slēgts',
            ]);

            return response()->json([
                'message' => 'Rīks arhivēts.',
                'tool' => $tool->fresh()->load('kategorija'),
            ]);
        }

        $this->deletePhotoIfExists($tool->foto);
        $tool->delete();

        return response()->json([
            'message' => 'Rīks dzēsts.',
        ]);
    }

    protected function validateTool(Request $request, bool $isCreate, ?Riks $existing = null): array
    {
        $rules = [
            'nosaukums' => ['required', 'string', 'max:100'],
            'cenadiena' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'daudzums' => ['required', 'integer', 'min:0'],
            'kategorijaID' => ['required', 'integer', 'exists:kategorija,kategorijaID'],
            'statuss' => ['required', 'string', Rule::in(['pieejams', 'aizņemts', 'remonts', 'slēgts'])],
            'apraksts' => ['nullable', 'string'],
            'kods' => ['nullable', 'string', 'max:50'],
            'zinols' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'redzamsKatalogs' => ['nullable', 'boolean'],
            'nomasilgumsmin' => ['nullable', 'integer', 'min:1'],
            'nomasilgumsmax' => ['nullable', 'integer', 'min:1'],
        ];

        if (! $isCreate) {
            $rules['nosaukums'][0] = 'sometimes';
            $rules['cenadiena'][0] = 'sometimes';
            $rules['daudzums'][0] = 'sometimes';
            $rules['kategorijaID'][0] = 'sometimes';
            $rules['statuss'][0] = 'sometimes';
        }

        if ($existing) {
            $rules['kategorijaID'][] = Rule::exists('kategorija', 'kategorijaID');
        }

        $validated = $request->validate($rules, [
            'nosaukums.required' => 'Nosaukums ir obligāts.',
            'nosaukums.max' => 'Nosaukums nedrīkst pārsniegt 100 rakstzīmes.',
            'cenadiena.required' => 'Cena ir obligāta.',
            'cenadiena.numeric' => 'Cena ir jābūt skaitlim.',
            'cenadiena.min' => 'Cena nedrīkst būt negatīva.',
            'daudzums.required' => 'Daudzums ir obligāts.',
            'daudzums.min' => 'Daudzums nedrīkst būt negatīvs.',
            'kategorijaID.exists' => 'Izvēlētā kategorija neeksistē.',
            'statuss.in' => 'Statuss nav derīgs.',
            'foto.image' => 'Foto ir jābūt attēla failam.',
            'foto.mimes' => 'Foto formāts drīkst būt tikai JPG, JPEG vai PNG.',
        ], [
            'nosaukums' => 'nosaukums',
            'cenadiena' => 'cena',
            'daudzums' => 'daudzums',
            'kategorijaID' => 'kategorija',
            'statuss' => 'statuss',
        ]);

        if ($request->has('redzamsKatalogs')) {
            $validated['redzamsKatalogs'] = (bool) $request->boolean('redzamsKatalogs');
        }

        return $validated;
    }

    protected function storePhoto($file): string
    {
        $path = $file->storePublicly('tools', 'public');

        return Storage::disk('public')->url($path);
    }

    protected function deletePhotoIfExists(?string $photoPath): void
    {
        if (! $photoPath) {
            return;
        }

        $relative = str_replace('/storage/', '', parse_url($photoPath, PHP_URL_PATH) ?? '');
        if ($relative && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
