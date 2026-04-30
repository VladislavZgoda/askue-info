<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUspdRequest;
use App\Http\Requests\UpdateUspdRequest;
use App\Http\Resources\UspdResource;
use App\Models\Uspd;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UspdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $uspds = Uspd::query()
            ->select(['id', 'model', 'serial_number'])
            ->when($search,
                fn (Builder $query) => $query->where(
                    fn (Builder $q) => $q->whereLike('serial_number', "%$search%")
                        ->orWhereLike('model', "%$search%")
                )
            )
            ->orderByDesc('id')
            ->cursorPaginate(12);

        return Inertia::render('Uspd/Index', [
            'uspds' => Inertia::scroll($uspds),
            'filter' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Uspd/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUspdRequest $request): RedirectResponse
    {
        $uspd = Uspd::create($request->validated());

        Inertia::flash('message', 'УСПД успешно создан.');

        return to_route('uspds.show', $uspd);
    }

    /**
     * Display the specified resource.
     */
    public function show(Uspd $uspd)
    {
        $uspd->load('simCards')
            ->loadExists('installationObject');

        return Inertia('Uspd/Show', [
            'uspd' => new UspdResource($uspd),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Uspd $uspd)
    {
        return inertia('Uspd/Edit', [
            'uspd' => new UspdResource($uspd),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUspdRequest $request, Uspd $uspd): RedirectResponse
    {
        $uspd->update($request->validated());

        Inertia::flash('message', 'УСПД успешно обновлён.');

        return to_route('uspds.show', $uspd);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uspd $uspd): RedirectResponse
    {
        $uspd->delete();

        Inertia::flash('message', 'УСПД успешно удалён.');

        return to_route('uspds.index');
    }
}
