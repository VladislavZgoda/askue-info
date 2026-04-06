<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeterRequest;
use App\Http\Resources\MeterResource;
use App\Models\Meter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MeterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $meters = Meter::whereLike('model', "%$search%")
            ->orWhereLike('serial_number', "%$search%")
            ->latest()
            ->get()
            ->toResourceCollection();

        return Inertia('Meter/Index', [
            'meters' => $meters,
            'filter' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Meter/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeterRequest $request): RedirectResponse
    {
        Meter::create($request->validated());

        Inertia::flash('message', 'Прибор учёта успешно создан.');

        return to_route('meters.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meter $meter)
    {
        $meter->load('simCards');

        return inertia('Meter/Show', new MeterResource($meter)->resolve());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
