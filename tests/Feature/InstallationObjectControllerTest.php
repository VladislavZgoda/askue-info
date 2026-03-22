<?php

use App\Http\Controllers\InstallationObjectController;
use App\Models\InstallationObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

it('can view a list of the :dataset installation objects', function (Collection $installationObjects) {
    $installationObjectCount = $installationObjects->count();
    $indexUrl = action([InstallationObjectController::class, 'index']);

    $response = $this->get($indexUrl);

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
    $showUrl = action([InstallationObjectController::class, 'show'], $installationObject);

    $installationObject->load(['meters', 'uspds']);

    $meterCount = $installationObject->meters_count;
    $uspdCount = $installationObject->uspds_count;

    $response = $this->get($showUrl);

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
                ->has('serial_number')
                ->where('id', $installationObject->meters->first()->id)
                ->where('model', $installationObject->meters->first()->model)
                ->where('serial_number', $installationObject->meters->first()->serial_number)
                ->whereType('id', 'integer')
                ->whereType('model', 'string')
                ->whereType('serial_number', 'string'))
            ->has('uspds', $uspdCount, fn (Assert $uspd) => $uspd
                ->has('id')
                ->has('model')
                ->has('serial_number')
                ->where('id', $installationObject->uspds->first()->id)
                ->where('model', $installationObject->uspds->first()->model)
                ->where('serial_number', $installationObject->uspds->first()->serial_number)
                ->whereType('id', 'integer')
                ->whereType('model', 'string')
                ->whereType('serial_number', 'integer'))
        );
})->with([
    'one meter and two uspds' => fn () => InstallationObject::factory()->hasMeters(1)->hasUspds(2)->create(),
    'two meters and one uspd' => fn () => InstallationObject::factory()->hasMeters(2)->hasUspds(1)->create(),
]);

it('can view the Create page', function () {
    $createUrl = action([InstallationObjectController::class, 'create']);
    $response = $this->get($createUrl);

    $response->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('InstallationObject/Create')
        );
});

it('can view the Edit page for the :dataset', function (InstallationObject $installationObject) {
    $editUrl = action([InstallationObjectController::class, 'edit'], $installationObject);
    $response = $this->get($editUrl);

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('InstallationObject/Edit')
            ->has('id')
            ->has('name')
            ->has('address')
            ->where('id', $installationObject->id)
            ->where('name', $installationObject->name)
            ->where('address', $installationObject->address)
            ->whereType('id', 'integer')
            ->whereType('name', 'string')
            ->whereType('address', 'string')
        );
})->with([
    'ТП-1' => fn () => InstallationObject::factory()->create(['name' => 'ТП-1']),
    'ТП-2' => fn () => InstallationObject::factory()->create(['name' => 'ТП-2']),
]);

describe('InstallationObject update', function () {
    it('can update the InstallationObject', function () {
        $installationObject = InstallationObject::factory()->create();

        $showUrl = action([InstallationObjectController::class, 'show'], $installationObject);
        $updateUrl = action([InstallationObjectController::class, 'update'], $installationObject);

        $response = $this->put($updateUrl, [
            'name' => 'Updated name',
            'address' => 'Updated address',
        ]);

        $response->assertValid(['name', 'address'])
            ->assertRedirect($showUrl)
            ->assertInertiaFlash('message', 'Данные успешно обновлены.');

        expect($installationObject->fresh())
            ->name->toBe('Updated name')
            ->address->toBe('Updated address');
    });

    it('validates required fields', function () {
        $installationObject = InstallationObject::factory()->create();
        $updateUrl = action([InstallationObjectController::class, 'update'], $installationObject);

        $response = $this->put($updateUrl, []);

        $response->assertRedirectBackWithErrors(['name', 'address']);
    });

    it('validates that the name is unique', function () {
        $installationObject1 = InstallationObject::factory()->create();
        $installationObject2 = InstallationObject::factory()->create();

        $updateUrl = action([InstallationObjectController::class, 'update'], $installationObject2);

        $response = $this->put($updateUrl, [
            'name' => $installationObject1->name,
        ]);

        $response->assertRedirectBackWithErrors(['name']);
    });

    it('validates the maximum length of input fields', function () {
        $installationObject = InstallationObject::factory()->create();
        $updateUrl = action([InstallationObjectController::class, 'update'], $installationObject);

        $response = $this->put($updateUrl, [
            'name' => Str::random(256),
            'address' => Str::random(256),
        ]);

        $response->assertRedirectBackWithErrors(['name', 'address']);
    });
});

describe('InstallationObject store', function () {
    it('can store the InstallationObject', function () {
        $storeUrl = action([InstallationObjectController::class, 'store']);

        $response = $this->post($storeUrl, [
            'name' => 'Created object',
            'address' => 'Created address',
        ]);

        $installationObject = InstallationObject::where('name', 'Created object')->first();

        $showUrl = action([InstallationObjectController::class, 'show'], $installationObject);

        $response->assertValid(['name', 'address'])
            ->assertRedirect($showUrl)
            ->assertInertiaFlash('message', 'Объект установки успешно создан.');
    });

    it('validates required fields', function () {
        $storeUrl = action([InstallationObjectController::class, 'store']);

        $response = $this->post($storeUrl, []);

        $response->assertRedirectBackWithErrors(['name', 'address']);
    });

    it('validates that the name is unique', function () {
        $installationObject = InstallationObject::factory()->create();

        $storeUrl = action([InstallationObjectController::class, 'store']);

        $response = $this->post($storeUrl, [
            'name' => $installationObject->name,
        ]);

        $response->assertRedirectBackWithErrors(['name']);
    });

    it('validates the maximum length of input fields', function () {
        $storeUrl = action([InstallationObjectController::class, 'store']);

        $response = $this->post($storeUrl, [
            'name' => Str::random(256),
            'address' => Str::random(256),
        ]);

        $response->assertRedirectBackWithErrors(['name', 'address']);
    });
});

it('deletes installationObject', function () {
    $installationObject = InstallationObject::factory()->create();

    $indexUrl = action([InstallationObjectController::class, 'index']);
    $destroyUrl = action([InstallationObjectController::class, 'destroy'], $installationObject);

    $response = $this->delete($destroyUrl);

    $this->assertDatabaseMissing('installation_objects', [
        'id' => $installationObject->id,
    ]);

    $response->assertRedirect($indexUrl)
        ->assertInertiaFlash('message', 'Объект установки успешно удалён.');
});
