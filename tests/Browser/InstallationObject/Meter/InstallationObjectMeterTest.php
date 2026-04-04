<?php

use App\Models\InstallationObject;
use App\Models\Meter;

it('renders form with installation object details', function () {
    $installationObject = InstallationObject::factory()->create([
        'name' => 'ТП-1',
        'address' => 'ул. Ленина, 1',
    ]);

    $url = route('installation-objects.meters.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("Добавить прибор учёта к $installationObject->name")
        ->assertSee($installationObject->address)
        ->assertSee('Прибор учёта')
        ->assertSelected('meter_id', '')
        ->assertButtonEnabled('Добавить')
        ->assertButtonEnabled('Очистить')
        ->assertNoJavaScriptErrors();
});

it('lists only unassigned meters in the select', function () {
    $installationObject = InstallationObject::factory()->create();
    $assignedMeter = Meter::factory()->create(['installation_object_id' => $installationObject->id]);
    $unassignedMeter = Meter::factory()->create(['installation_object_id' => null]);

    $url = route('installation-objects.meters.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSourceHas("{$unassignedMeter->model} №{$unassignedMeter->serial_number}")
        ->assertSourceMissing("{$assignedMeter->model} №{$assignedMeter->serial_number}")
        ->assertNoJavaScriptErrors();
});

it('submits the form with a selected meter, associates it and redirects', function () {
    $installationObject = InstallationObject::factory()->create();

    $meter = Meter::factory()->create([
        'installation_object_id' => null,
        'model' => 'CE303',
        'serial_number' => '123456789',
    ]);

    $url = route('installation-objects.meters.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('meter_id', $meter->id)
        ->click('Добавить')
        ->assertUrlIs(route('installation-objects.show', $installationObject))
        ->assertSee('Прибор учёта успешно связан с объектом.')
        ->assertNoJavaScriptErrors();

    expect($meter->fresh()->installation_object_id)->toBe($installationObject->id);
});

it('shows a validation error when submitting the form without selecting a meter', function () {
    $installationObject = InstallationObject::factory()->create();
    Meter::factory()->create(['installation_object_id' => null]);

    $url = route('installation-objects.meters.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->click('Добавить')
        ->assertSee('Поле является обязательным.')
        ->assertNoJavaScriptErrors();
});

it('resets the select back to placeholder', function () {
    $installationObject = InstallationObject::factory()->create();

    $meter = Meter::factory()->create([
        'installation_object_id' => null,
        'model' => 'Меркурий',
        'serial_number' => 'SN-999',
    ]);

    $url = route('installation-objects.meters.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('meter_id', $meter->id)
        ->assertSelected('meter_id', $meter->id)
        ->click('Очистить')
        ->assertSelected('meter_id', '')
        ->assertNoJavaScriptErrors();
});

it('returns to the previous page', function () {
    $installationObject = InstallationObject::factory()->create();

    $showUrl = route('installation-objects.show', $installationObject);

    $page = $this->visit($showUrl)
        ->on()
        ->mobile()
        ->navigate(route('installation-objects.meters.create', $installationObject));

    $page->click('Назад')
        ->assertUrlIs($showUrl)
        ->assertNoJavaScriptErrors();
});
