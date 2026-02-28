<?php

namespace App\Http\Controllers;

use App\Models\InstallationObject;
use Illuminate\Http\Request;

class InstallationObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('InstallationObject/Index', [
            'installationObjects' => InstallationObject::all(['id', 'name', 'address']),
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
    public function show(InstallationObject $installationObject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstallationObject $installationObject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstallationObject $installationObject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstallationObject $installationObject)
    {
        //
    }
}
