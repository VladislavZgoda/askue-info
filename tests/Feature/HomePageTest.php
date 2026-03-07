<?php

use App\Models\InstallationObject;

it('visits the Home page', function () {
    $page = visit('/')->on()->mobile();

    $page->assertSeeLink('Просмотр объектов установки');
});

it('navigates from the Home page to the InstallationObjects page', function () {
    $installationObject = InstallationObject::factory()->create();

    $page = visit('/')->on()->mobile();

    $page->click('Просмотр объектов установки')
        ->assertUrlIs(route('installation-objects.index'))
        ->assertSee($installationObject->name)
        ->assertSee($installationObject->address);
});
