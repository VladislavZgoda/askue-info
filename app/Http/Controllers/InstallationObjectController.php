<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstallationObjectRequest;
use App\Http\Requests\UpdateInstallationObjectRequest;
use App\Models\InstallationObject;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class InstallationObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('InstallationObject/Index', [
            'installationObjects' => InstallationObject::all()
                ->toResourceCollection(),
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

        $data = [
            'id' => $installationObject->id,
            'name' => $installationObject->name,
            'meters' => $installationObject->meters->map(fn ($meter) => [
                'id' => $meter->id,
                'model' => $meter->model,
                'serialNumber' => $meter->serial_number,
            ]),
            'uspds' => $installationObject->uspds->map(fn ($uspd) => [
                'id' => $uspd->id,
                'model' => $uspd->model,
                'serialNumber' => $uspd->serial_number,
            ]),
        ];

        return inertia('InstallationObject/Show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstallationObject $installationObject)
    {
        return inertia('InstallationObject/Edit', $installationObject->only(['id', 'name', 'address']));
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
