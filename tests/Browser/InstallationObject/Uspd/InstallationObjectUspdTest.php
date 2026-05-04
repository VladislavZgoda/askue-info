<?php

use App\Models\InstallationObject;
use App\Models\Uspd;

it('renders form with installation object details', function () {
    $installationObject = InstallationObject::factory()->create();

    $url = route('installation-objects.uspds.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("Добавить УСПД к $installationObject->name")
        ->assertSee($installationObject->address)
        ->assertSee('УСПД')
        ->assertSelected('uspd_id', '')
        ->assertButtonEnabled('Добавить')
        ->assertButtonEnabled('Очистить')
        ->assertNoJavaScriptErrors();
});

it('lists only unassigned uspds in the select', function () {
    $installationObject = InstallationObject::factory()->create();
    $assignedUspd = Uspd::factory()->for($installationObject)->create();

    $unassignedUspd = Uspd::factory()
        ->withoutInstallationObject()
        ->create();

    $url = route('installation-objects.uspds.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSourceHas("{$unassignedUspd->model} №{$unassignedUspd->serial_number}")
        ->assertSourceMissing("{$assignedUspd->model} №{$assignedUspd->serial_number}")
        ->assertNoJavaScriptErrors();
});

it('submits the form with a selected uspd, associates it and redirects', function () {
    $installationObject = InstallationObject::factory()->create();

    $uspd = Uspd::factory()
        ->withoutInstallationObject()
        ->create();

    $url = route('installation-objects.uspds.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('uspd_id', $uspd->id)
        ->click('Добавить')
        ->assertUrlIs(route('installation-objects.show', $installationObject))
        ->assertSee('УСПД успешно связан с объектом.')
        ->assertNoJavaScriptErrors();

    expect($uspd->fresh()->installation_object_id)->toBe($installationObject->id);
});

it('shows a validation error when submitting the form without selecting a uspd', function () {
    $installationObject = InstallationObject::factory()->create();

    $url = route('installation-objects.uspds.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->click('Добавить')
        ->assertSee('Поле является обязательным.')
        ->assertNoJavaScriptErrors();
});

it('resets the select back to placeholder', function () {
    $installationObject = InstallationObject::factory()->create();

    $uspd = Uspd::factory()
        ->withoutInstallationObject()
        ->create();

    $url = route('installation-objects.uspds.create', $installationObject);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('uspd_id', $uspd->id)
        ->assertSelected('uspd_id', $uspd->id)
        ->click('Очистить')
        ->assertSelected('uspd_id', '')
        ->assertNoJavaScriptErrors();
});

it('returns to the previous page', function () {
    $installationObject = InstallationObject::factory()->create();
    $showUrl = route('installation-objects.show', $installationObject);

    $page = $this->visit($showUrl)
        ->on()
        ->mobile()
        ->navigate(route('installation-objects.uspds.create', $installationObject));

    $page->click('Назад')
        ->assertUrlIs($showUrl)
        ->assertNoJavaScriptErrors();
});
