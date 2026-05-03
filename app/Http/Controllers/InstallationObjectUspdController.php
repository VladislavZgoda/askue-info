<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstallationObjectResource;
use App\Models\InstallationObject;
use App\Models\Uspd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstallationObjectUspdController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(InstallationObject $installationObject)
    {
        $unassignedUspds = Uspd::doesntHave('installationObject')
            ->get(['id', 'model', 'serial_number']);

        return inertia('InstallationObject/Uspd/Create', [
            'installationObject' => new InstallationObjectResource($installationObject),
            'unassignedUspds' => $unassignedUspds,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, InstallationObject $installationObject): RedirectResponse
    {
        $validated = $request->validate([
            'uspd_id' => ['required', 'integer', 'exists:uspds,id'],
        ]);

        $uspd = Uspd::find($validated['uspd_id']);
        $uspd->installationObject()->associate($installationObject);
        $uspd->save();

        Inertia::flash('message', 'УСПД успешно связан с объектом.');

        return to_route('installation-objects.show', $installationObject);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstallationObject $installationObject, Uspd $uspd): RedirectResponse
    {
        $uspd->installationObject()->disassociate();
        $uspd->save();

        return Inertia::flash('message', 'УСПД успешно отсоединился.')
            ->back();
    }
}
