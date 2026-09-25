<?php

namespace App\Http\Controllers;

use App\Models\Pasutijums;
use App\Models\PasutijumaRiks;
use App\Models\Riks;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private const STATUSES = ['Jauns', 'Apstiprinats', 'Izpildits', 'Atcelts'];

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'riki' => ['required', 'array', 'min:1'],
            'riki.*.rikID' => ['required', 'integer', 'distinct', 'exists:riks,rikID'],
            'riki.*.daudzums' => ['required', 'integer', 'min:1'],
            'riki.*.nomasSakums' => ['required', 'date_format:d.m.Y'],
            'riki.*.nomasBeigums' => ['required', 'date_format:d.m.Y'],
        ], [
            'riki.required' => 'Jānorāda vismaz viens rīks.',
            'riki.min' => 'Jānorāda vismaz viens rīks.',
            'riki.*.daudzums.min' => 'Daudzumam jābūt lielākam par 0.',
            'riki.*.nomasSakums.date_format' => 'Datumam jābūt DD.MM.YYYY formātā.',
            'riki.*.nomasBeigums.date_format' => 'Datumam jābūt DD.MM.YYYY formātā.',
        ]);

        $order = DB::transaction(function () use ($validated, $request): Pasutijums {
            $total = 0;
            $items = [];

            foreach ($validated['riki'] as $item) {
                $start = Carbon::createFromFormat('d.m.Y', $item['nomasSakums'])->startOfDay();
                $end = Carbon::createFromFormat('d.m.Y', $item['nomasBeigums'])->startOfDay();

                if ($end->lt($start)) {
                    abort(response()->json([
                        'message' => 'Nomas beigu datumam jābūt pēc sākuma datuma.',
                    ], 422));
                }

                $tool = Riks::query()->lockForUpdate()->findOrFail($item['rikID']);
                $reserved = PasutijumaRiks::query()
                    ->where('rikID', $tool->getKey())
                    ->whereHas('pasutijums', fn ($query) => $query->where('statuss', '!=', 'Atcelts'))
                    ->where('nomassakums', '<=', $end->toDateString())
                    ->where('nomasbeigums', '>=', $start->toDateString())
                    ->sum('daudzums_pozicija');

                if ($reserved + $item['daudzums'] > $tool->daudzums) {
                    abort(response()->json([
                        'message' => 'Šis instruments jau ir aizņemts šajos datumos',
                    ], 422));
                }

                $days = $start->diffInDays($end) + 1;
                $total += (float) $tool->cenadiena * $days * $item['daudzums'];
                $items[] = [
                    'rikID' => $tool->getKey(),
                    'daudzums_pozicija' => $item['daudzums'],
                    'nomassakums' => $start->toDateString(),
                    'nomasbeigums' => $end->toDateString(),
                ];
            }

            $order = Pasutijums::create([
                'kopsumma' => number_format($total, 2, '.', ''),
                'statuss' => 'Jauns',
                'lietotajID' => $request->user()->getKey(),
            ]);

            foreach ($items as $item) {
                $order->riki()->attach($item['rikID'], [
                    'daudzums_pozicija' => $item['daudzums_pozicija'],
                    'nomassakums' => $item['nomassakums'],
                    'nomasbeigums' => $item['nomasbeigums'],
                ]);
            }

            return $order->load('riki');
        });

        return response()->json($order, 201);
    }

    public function mine(Request $request): JsonResponse
    {
        return response()->json(
            Pasutijums::with('riki')
                ->where('lietotajID', $request->user()->getKey())
                ->latest('pasutijumsID')
                ->get()
        );
    }

    public function cancel(Request $request, Pasutijums $order): JsonResponse
    {
        if ($order->lietotajID !== $request->user()->getKey()) {
            return response()->json(['message' => 'Jums nav tiesību atcelt šo pasūtījumu.'], 403);
        }

        if ($order->statuss !== 'Jauns') {
            return response()->json([
                'message' => 'Atcelt var tikai pasūtījumu ar statusu "Jauns".',
            ], 422);
        }

        $order->update(['statuss' => 'Atcelts']);

        return response()->json($order->fresh()->load('riki'));
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'statuss' => ['sometimes', Rule::in(self::STATUSES)],
            'datums_no' => ['sometimes', 'date_format:d.m.Y'],
            'datums_lidz' => ['sometimes', 'date_format:d.m.Y'],
        ]);

        $orders = Pasutijums::with(['lietotajs', 'riki'])
            ->when(isset($validated['statuss']), fn ($query) => $query->where('statuss', $validated['statuss']))
            ->when(isset($validated['datums_no']), fn ($query) => $query->whereDate('izveidesdatums', '>=', Carbon::createFromFormat('d.m.Y', $validated['datums_no'])->toDateString()))
            ->when(isset($validated['datums_lidz']), fn ($query) => $query->whereDate('izveidesdatums', '<=', Carbon::createFromFormat('d.m.Y', $validated['datums_lidz'])->toDateString()))
            ->latest('pasutijumsID')
            ->get();

        return response()->json($orders);
    }

    public function updateStatus(Request $request, Pasutijums $order): JsonResponse
    {
        $validated = $request->validate([
            'statuss' => ['required', Rule::in(self::STATUSES)],
        ]);

        $order->update($validated);

        return response()->json($order->fresh()->load(['lietotajs', 'riki']));
    }
}