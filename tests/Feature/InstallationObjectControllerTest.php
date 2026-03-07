<?php

use App\Models\InstallationObject;
use Inertia\Testing\AssertableInertia as Assert;

it('can view the list of installation objects', function () {
    InstallationObject::factory()
        ->count(5)
        ->create();

    $response = $this->get(route('installation-objects.index'));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('InstallationObject/Index')
            ->has('installationObjects', 5, fn (Assert $page) => $page
                ->has('id')
                ->has('name')
                ->has('address')
                ->whereType('id', 'integer')
                ->whereType('name', 'string')
                ->whereType('address', 'string')
            )
        );
});

it('can view the installation object', function () {
    InstallationObject::factory()
        ->hasMeters(3)
        ->hasUspds(3)
        ->create();

    $installationObject = InstallationObject::with(['meters', 'uspds'])
        ->first();

    $response = $this->get(route('installation-objects.show', $installationObject));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('InstallationObject/Show')
            ->has('id')
            ->has('name')
            ->where('id', $installationObject->id)
            ->where('name', $installationObject->name)
            ->whereType('id', 'integer')
            ->whereType('name', 'string')
            ->has('meters', 3, fn (Assert $meter) => $meter
                ->has('id')
                ->has('model')
                ->has('serialNumber')
                ->where('id', $installationObject->meters->first()->id)
                ->where('model', $installationObject->meters->first()->model)
                ->where('serialNumber', $installationObject->meters->first()->serial_number)
                ->whereType('id', 'integer')
                ->whereType('model', 'string')
                ->whereType('serialNumber', 'string'))
            ->has('uspds', 3, fn (Assert $uspd) => $uspd
                ->has('id')
                ->has('model')
                ->has('serialNumber')
                ->where('id', $installationObject->uspds->first()->id)
                ->where('model', $installationObject->uspds->first()->model)
                ->where('serialNumber', $installationObject->uspds->first()->serial_number)
                ->whereType('id', 'integer')
                ->whereType('model', 'string')
                ->whereType('serialNumber', 'integer'))
        );
});
