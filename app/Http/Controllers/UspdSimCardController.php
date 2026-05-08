<?php

namespace App\Http\Controllers;

use App\Http\Resources\UspdResource;
use App\Models\SimCard;
use App\Models\Uspd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UspdSimCardController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Uspd $uspd)
    {
        $simCards = SimCard::query()
            ->doesntHave('meters')
            ->doesntHave('uspd')
            ->get(['id', 'number', 'operator']);

        return inertia('Uspd/SimCard/Create', [
            'uspd' => new UspdResource($uspd),
            'simCards' => $simCards,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Uspd $uspd): RedirectResponse
    {
        $validated = $request->validate([
            'sim_card_id' => ['required', 'integer', 'exists:sim_cards,id'],
        ], [
            'sim_card_id.required' => 'Выберите сим-карту.',
        ]);

        try {
            $simCard = SimCard::find($validated['sim_card_id']);
            $simCard->uspd()->associate($uspd);
            $simCard->save();
        } catch (\Exception $error) {
            throw ValidationException::withMessages([
                'sim_card_id' => $error->getMessage(),
            ]);
        }

        Inertia::flash('message', 'Сим-карта успешно привязана к УСПД.');

        return to_route('uspds.show', $uspd);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uspd $uspd, SimCard $simCard): RedirectResponse
    {
        $simCard->uspd()->disassociate();
        $simCard->save();

        return Inertia::flash('message', 'Сим-карта успешно отвязана.')
            ->back();
    }
}
