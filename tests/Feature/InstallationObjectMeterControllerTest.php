<?php

use App\Http\Controllers\InstallationObjectMeterController;
use App\Models\InstallationObject;
use App\Models\Meter;
use Inertia\Testing\AssertableInertia as Assert;

describe('InstallationObjectMeterController create action', function () {
    it('can view the Create page', function () {
        $installationObject = InstallationObject::factory()->create();

        $unassignedMeters = Meter::factory()
            ->count(3)
            ->create(['installation_object_id' => null]);

        $response = $this->get(action([InstallationObjectMeterController::class, 'create'], $installationObject));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('InstallationObject/Meter/Create')
                    ->has(
                        'installationObject',
                        fn (Assert $prop) => $prop
                            ->where('id', $installationObject->id)
                            ->etc()
                    )
                    ->has('unassignedMeters', $unassignedMeters->count())
            );
    });

    it('only includes meters without an installation object', function () {
        $installationObject = InstallationObject::factory()->create();
        $unassignedMeter = Meter::factory()->create(['installation_object_id' => null]);

        Meter::factory()->create(['installation_object_id' => $installationObject->id]);

        $response = $this->get(action([InstallationObjectMeterController::class, 'create'], $installationObject));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->has('unassignedMeters', 1)
                    ->where('unassignedMeters.0.id', $unassignedMeter->id)
            );
    });
});

describe('InstallationObjectMeterController store action', function () {
    it('associates the meter with the installation object and redirects', function () {
        $installationObject = InstallationObject::factory()->create();
        $meter = Meter::factory()->create(['installation_object_id' => null]);

        $response = $this->post(
            action([InstallationObjectMeterController::class, 'store'], $installationObject),
            ['meter_id' => $meter->id]
        );

        $response->assertRedirect(route('installation-objects.show', $installationObject))
            ->assertInertiaFlash('message', 'Прибор учёта успешно связан с объектом.');

        expect($meter->fresh()->installation_object_id)->toBe($installationObject->id);
    });

    it('requires valid data to associate the meter with the installation object', function (string $field, mixed $value) {
        $installationObject = InstallationObject::factory()->create();

        $this->post(route('installation-objects.meters.store', $installationObject), [])
            ->assertRedirectBackWithErrors([$field]);
    })->with([
        'meter_id is required' => ['meter_id', ''],
    ]);
});

describe('InstallationObjectMeterController destroy action', function () {
    it('disassociates the meter from its installation object and redirects back', function () {
        $installationObject = InstallationObject::factory()->create();
        $meter = Meter::factory()->create(['installation_object_id' => $installationObject->id]);

        $this->from(route('installation-objects.show', $installationObject))
            ->delete(route('meters.destroy', $meter))
            ->assertRedirect(route('installation-objects.show', $installationObject))
            ->assertInertiaFlash('message', 'Прибор учёта успешно отсоединился.');

        expect($meter->fresh()->installation_object_id)->toBeNull();
    });
});
