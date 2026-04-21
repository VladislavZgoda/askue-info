<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeterResource;
use App\Models\Meter;
use App\Models\SimCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MeterSimCardController extends Controller
{
    /**
     * Show the form for attaching a SimCard to a Meter.
     */
    public function create(Meter $meter)
    {
        $simCards = SimCard::doesntHave('uspd')
            ->where(function (Builder $query) use ($meter) {
                $query->whereDoesntHave('meters')
                    ->orWhereHas('meters', function (Builder $q) use ($meter) {
                        $q->whereBelongsTo($meter->installationObject);
                    });
            })
            ->whereDoesntHave('meters', function (Builder $query) use ($meter) {
                $query->where('meter_sim_card.meter_id', $meter->id);
            })
            ->get(['id', 'number', 'operator']);

        return inertia('Meter/SimCard/Create', [
            'meter' => new MeterResource($meter),
            'simCards' => $simCards,
        ]);
    }

    /**
     * Attach a SimCard to a Meter.
     */
    public function store(Request $request, Meter $meter): RedirectResponse
    {
        $validated = $request->validate([
            'sim_card_id' => ['required', 'integer', 'exists:sim_cards,id'],
        ], [
            'sim_card_id.required' => 'Выберите сим-карту.',
        ]);

        try {
            $meter->simCards()->attach($validated['sim_card_id']);
        } catch (\Exception $error) {
            throw ValidationException::withMessages([
                'sim_card_id' => $error->getMessage(),
            ]);
        }

        Inertia::flash('message', 'Сим-карта успешно привязана к прибору учёта.');

        return to_route('meters.show', $meter);
    }

    /**
     * Detach a SimCard from a Meter.
     */
    public function destroy(Meter $meter, SimCard $simCard): RedirectResponse
    {
        $meter->simCards()->detach($simCard->id);

        return Inertia::flash('message', 'Сим-карта успешно отвязана от прибора учёта.')
            ->back();
    }
}
