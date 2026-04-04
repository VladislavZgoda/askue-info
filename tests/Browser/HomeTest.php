<?php

use App\Models\InstallationObject;
use App\Models\Meter;

it('visits the Home page', function () {
    $page = visit('/')->on()->mobile();

    $page->assertSeeLink('Просмотр объектов установки')
        ->assertSeeLink('Просмотр приборов учёта');
});

it('navigates from the Home page to installation-objects.index using the link', function () {
    $installationObject = InstallationObject::factory()->create();

    $page = visit('/')->on()->mobile();

    $page->click('Просмотр объектов установки')
        ->assertUrlIs(route('installation-objects.index'))
        ->assertSee($installationObject->name)
        ->assertSee($installationObject->address);
});

it('navigates from the Home page to meters.index using the link', function () {
    $meter = Meter::factory()->create();

    $page = visit('/')->on()->mobile();

    $page->click('Просмотр приборов учёта')
        ->assertUrlIs(route('meters.index'))
        ->assertSee($meter->model)
        ->assertSee($meter->serial_number);
});
