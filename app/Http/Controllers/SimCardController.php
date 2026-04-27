<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSimCardRequest;
use App\Http\Requests\UpdateSimCardRequest;
use App\Http\Resources\SimCardResource;
use App\Models\SimCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SimCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $simCards = SimCard::query()
            ->select(['id', 'number', 'operator'])
            ->when($search,
                fn (Builder $query) => $query->where(
                    fn (Builder $q) => $q->whereLike('number', "%$search%")
                        ->orWhereLike('operator', "%$search%")
                )
            )
            ->orderByDesc('id')
            ->cursorPaginate(12);

        return Inertia::render('SimCard/Index', [
            'simCards' => Inertia::scroll($simCards),
            'filter' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('SimCard/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSimCardRequest $request): RedirectResponse
    {
        $simCard = SimCard::create($request->validated());

        Inertia::flash('message', 'Сим-карта успешно создана.');

        return to_route('sim-cards.show', $simCard);
    }

    /**
     * Display the specified resource.
     */
    public function show(SimCard $simCard)
    {
        $simCard->loadExists(['meters', 'uspd']);

        return Inertia('SimCard/Show', new SimCardResource($simCard)->resolve());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SimCard $simCard)
    {
        return inertia('SimCard/Edit', [
            'simCard' => new SimCardResource($simCard),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSimCardRequest $request, SimCard $simCard): RedirectResponse
    {
        $simCard->update($request->validated());

        Inertia::flash('message', 'Сим-карта успешно обновлена.');

        return to_route('sim-cards.show', $simCard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SimCard $simCard): RedirectResponse
    {
        $simCard->delete();

        Inertia::flash('message', 'Сим-карта успешно удалена.');

        return to_route('sim-cards.index');
    }
}
