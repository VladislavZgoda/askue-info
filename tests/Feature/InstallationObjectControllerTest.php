<?php

use App\Models\InstallationObject;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

it('can view a list of the :dataset installation objects', function (Collection $installationObjects) {
    $installationObjectCount = $installationObjects->count();

    $response = $this->get(route('installation-objects.index'));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('InstallationObject/Index')
            ->has('installationObjects', $installationObjectCount,
                fn (Assert $page) => $page
                    ->has('id')
                    ->has('name')
                    ->has('address')
                    ->whereType('id', 'integer')
                    ->whereType('name', 'string')
                    ->whereType('address', 'string')
            )
        );
})->with([
    'three' => fn () => InstallationObject::factory()->count(3)->create(),
    'five' => fn () => InstallationObject::factory()->count(5)->create(),
]);

it('can view the installation object with :dataset', function (InstallationObject $installationObject) {
    $installationObject->load(['meters', 'uspds']);

    $meterCount = $installationObject->meters_count;
    $uspdCount = $installationObject->uspds_count;

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
            ->has('meters', $meterCount, fn (Assert $meter) => $meter
                ->has('id')
                ->has('model')
                ->has('serialNumber')
                ->where('id', $installationObject->meters->first()->id)
                ->where('model', $installationObject->meters->first()->model)
                ->where('serialNumber', $installationObject->meters->first()->serial_number)
                ->whereType('id', 'integer')
                ->whereType('model', 'string')
                ->whereType('serialNumber', 'string'))
            ->has('uspds', $uspdCount, fn (Assert $uspd) => $uspd
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
})->with([
    'one meter and two uspds' => fn () => InstallationObject::factory()->hasMeters(1)->hasUspds(2)->create(),
    'two meters and one uspd' => fn () => InstallationObject::factory()->hasMeters(2)->hasUspds(1)->create(),
]);
