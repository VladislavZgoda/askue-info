<?php

use App\Models\InstallationObject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('visits the Welcome page', function () {
    $page = visit('/')->on()->mobile();

    $page->assertSeeLink('Просмотр объектов установки');
});

it('navigates from the Welcome page to the InstallationObjects page', function () {
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
