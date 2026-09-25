<?php

namespace App\Http\Controllers;

use App\Models\Kategorija;
use App\Models\Riks;
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
        $tools = Riks::with('kategorija')
            ->when(! $this->isAdministrator($request), fn ($query) => $query->where('redzamsKatalogs', true))
            ->orderBy('rikID')
            ->get();

        return response()->json($tools);
    }

    public function show(Request $request, Riks $rik): JsonResponse
    {
        if (! $rik->redzamsKatalogs && ! $this->isAdministrator($request)) {
            return response()->json(['message' => 'Rīks nav pieejams katalogā.'], 404);
        }

        return response()->json($rik->load('kategorija'));
    }

    public function categories(): JsonResponse
    {
        return response()->json(Kategorija::orderBy('nosaukums')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateTool($request);
        $validated['foto'] = $this->storePhoto($request->file('foto'));

        $tool = Riks::create($validated)->load('kategorija');

        return response()->json($tool, 201);
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
        $request->validate([
            'dzeshanas_modelis' => ['required', Rule::in(['dzest', 'arhivet'])],
        ], [
            'dzeshanas_modelis.in' => 'Dzēšanas modelim jābūt "dzest" vai "arhivet".',
        ]);

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
            'required' => 'Lauks :attribute ir obligāts.',
            'nosaukums.max' => 'Nosaukums nedrīkst pārsniegt 100 rakstzīmes.',
            'cenadiena.decimal' => 'Cenai jābūt ar ne vairāk kā 2 cipariem aiz komata.',
            'cenadiena.min' => 'Cena nedrīkst būt negatīva.',
            'daudzums.min' => 'Daudzums nedrīkst būt negatīvs.',
            'kategorijaID.exists' => 'Norādītā kategorija neeksistē.',
            'statuss.in' => 'Norādītais statuss nav atļauts.',
            'foto.image' => 'Fotoattēlam jābūt derīgam attēlam.',
            'foto.mimes' => 'Fotoattēlam jābūt JPG vai PNG formātā.',
        ], [
            'nosaukums' => 'nosaukums',
            'cenadiena' => 'cena dienā',
            'daudzums' => 'daudzums',
            'kategorijaID' => 'kategorija',
            'statuss' => 'statuss',
            'foto' => 'foto',
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
}