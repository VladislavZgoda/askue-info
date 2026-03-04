<?php

use App\Models\InstallationObject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('visits the Home page', function () {
    $page = visit('/')->on()->mobile();

    $page->assertSeeLink('Просмотр объектов установки');
});

it('navigates from the Home page to the Index page of the InstallationObjectController', function () {
    InstallationObject::factory()->create([
        'name' => 'ТП-1',
        'address' => 'ул. Сосновая 10',
    ]);

    $page = visit('/')->on()->mobile();

    $page->click('Просмотр объектов установки')
        ->assertPathIs('/installation-objects')
        ->assertSee('ТП-1')
        ->assertSee('ул. Сосновая 10');
});
