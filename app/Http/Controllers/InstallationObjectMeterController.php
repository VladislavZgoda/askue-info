<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstallationObjectResource;
use App\Models\InstallationObject;
use App\Models\Meter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstallationObjectMeterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(InstallationObject $installationObject)
    {
        $unassignedMeters = Meter::whereNull('installation_object_id')
            ->get()
            ->toResourceCollection();

        return inertia('InstallationObject/Meter/Create', [
            'installationObject' => new InstallationObjectResource($installationObject),
            'unassignedMeters' => $unassignedMeters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, InstallationObject $installationObject): RedirectResponse
    {
        $validated = $request->validate([
            'meter_id' => ['required'],
        ]);

        $meter = Meter::findOrFail($validated['meter_id']);

        $meter->installationObject()->associate($installationObject);
        $meter->save();

        Inertia::flash('message', 'Прибор учёта успешно связан с объектом.');

        return to_route('installation-objects.show', $installationObject);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstallationObject $installationObject, Meter $meter): RedirectResponse
    {
        $meter->installationObject()->disassociate();
        $meter->save();

        return Inertia::flash('message', 'Прибор учёта успешно отсоединился.')
            ->back();
    }
}
