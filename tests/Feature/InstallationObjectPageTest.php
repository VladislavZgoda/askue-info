<?php

use App\Models\InstallationObject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders a page with data', function () {
    InstallationObject::factory()
        ->hasMeters(2)
        ->hasUspds(2)
        ->create();

    $installationObject = InstallationObject::with(['meters', 'uspds'])
        ->first();

    $page = visit("/installation-objects/$installationObject->id")
        ->on()
        ->mobile();

    $page->assertSee($installationObject->name)
        ->assertSeeLink('Добавить УСПД')
        ->assertSeeLink('Добавить ПУ')
        ->assertSeeLink('Назад')
        ->assertCount('.group\/item-group', 2);

    foreach ($installationObject->meters as $meter) {
        $page->assertSee($meter->model)
            ->assertSee($meter->serial_number);
    }

    foreach ($installationObject->uspds as $uspd) {
        $page->assertSee($uspd->model)
            ->assertSee($uspd->serial_number);
    }
});
