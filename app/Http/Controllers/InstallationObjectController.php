<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstallationObjectRequest;
use App\Http\Requests\UpdateInstallationObjectRequest;
use App\Http\Resources\InstallationObjectResource;
use App\Models\InstallationObject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstallationObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $installationObjects = InstallationObject::whereLike('name', "%$search%")
            ->orWhereLike('address', "%$search%")
            ->latest()
            ->get()
            ->toResourceCollection();

        return inertia('InstallationObject/Index', [
            'installationObjects' => $installationObjects,
            'filter' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('InstallationObject/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstallationObjectRequest $request): RedirectResponse
    {
        $installationObject = InstallationObject::create($request->validated());

        Inertia::flash('message', 'Объект установки успешно создан.');

        return to_route('installation-objects.show', $installationObject);
    }

    /**
     * Display the specified resource.
     */
    public function show(InstallationObject $installationObject)
    {
        $installationObject->load(['meters', 'uspds']);

        return inertia(
            'InstallationObject/Show',
            new InstallationObjectResource($installationObject)
                ->resolve()
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstallationObject $installationObject)
    {
        return inertia(
            'InstallationObject/Edit', [
                'installationObject' => new InstallationObjectResource($installationObject),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstallationObjectRequest $request, InstallationObject $installationObject): RedirectResponse
    {
        $installationObject->update($request->validated());

        Inertia::flash('message', 'Данные успешно обновлены.');

        return to_route('installation-objects.show', ['installation_object' => $installationObject->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstallationObject $installationObject)
    {
        $installationObject->delete();

        Inertia::flash('message', 'Объект установки успешно удалён.');

        return to_route('installation-objects.index');
    }
}
