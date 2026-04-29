<?php

namespace App\Http\Controllers;

use App\Http\Resources\UspdResource;
use App\Models\Uspd;
use Illuminate\Database\Eloquent\Builder;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Uspd $uspd)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uspd $uspd)
    {
        //
    }
}
