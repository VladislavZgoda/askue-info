<?php

use App\Http\Controllers\InstallationObjectUspdController;
use App\Models\InstallationObject;
use App\Models\User;
use App\Models\Uspd;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $user = User::factory()->create();
    $this->actingAs($user);
});

describe('InstallationObjectUspdController create action', function () {
    it('can view the Create page', function () {
        $installationObject = InstallationObject::factory()->create();

        $unassignedUspds = Uspd::factory()
            ->count(3)
            ->withoutInstallationObject()
            ->create();

        $response = $this->get(action([InstallationObjectUspdController::class, 'create'], $installationObject));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('InstallationObject/Uspd/Create')
                    ->has(
                        'installationObject',
                        fn (Assert $prop) => $prop
                            ->where('id', $installationObject->id)
                            ->where('name', $installationObject->name)
                            ->where('address', $installationObject->address)
                    )
                    ->has('unassignedUspds', $unassignedUspds->count())
            );
    });

    it('only includes uspds without an installation object', function () {
        $installationObject = InstallationObject::factory()->create();
        Uspd::factory()->for($installationObject)->create();

        $unassignedUspd = Uspd::factory()
            ->withoutInstallationObject()
            ->create();

        $response = $this->get(action([InstallationObjectUspdController::class, 'create'], $installationObject));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->has('unassignedUspds', 1)
                    ->where('unassignedUspds.0.id', $unassignedUspd->id)
            );
    });
});

describe('InstallationObjectUspdController store action', function () {
    it('associates the uspd with the installation object and redirects', function () {
        $installationObject = InstallationObject::factory()->create();

        $uspd = Uspd::factory()
            ->withoutInstallationObject()
            ->create();

        $response = $this->post(
            action([InstallationObjectUspdController::class, 'store'], $installationObject),
            ['uspd_id' => $uspd->id]
        );

        $response->assertRedirect(route('installation-objects.show', $installationObject))
            ->assertInertiaFlash('message', 'УСПД успешно связан с объектом.');

        expect($uspd->fresh()->installation_object_id)->toBe($installationObject->id);
    });

    it('requires valid data to associate the uspd with the installation object', function (string $field, mixed $value) {
        $installationObject = InstallationObject::factory()->create();
        $action = action([InstallationObjectUspdController::class, 'store'], $installationObject);

        $this->post($action, [$field => $value])
            ->assertRedirectBackWithErrors([$field]);
    })->with([
        'uspd_id is required' => ['uspd_id', ''],
        'uspd_id must be an integer' => ['uspd_id', 'a'],
        'uspd_id must exist in db' => ['uspd_id', 1],
    ]);
});

describe('InstallationObjectUspdController destroy action', function () {
    it('disassociates the uspd from its installation object and redirects back', function () {
        $installationObject = InstallationObject::factory()->create();
        $uspd = Uspd::factory()->for($installationObject)->create();

        $this->from(route('installation-objects.show', $installationObject))
            ->delete(route('installation-objects.uspds.destroy', [$installationObject, $uspd]))
            ->assertRedirect(route('installation-objects.show', $installationObject))
            ->assertInertiaFlash('message', 'УСПД успешно отсоединился.');

        expect($uspd->fresh()->installation_object_id)->toBeNull();
    });
});
