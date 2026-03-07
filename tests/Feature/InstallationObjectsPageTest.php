<?php

use App\Models\InstallationObject;

it('shows the list of installation objects', function () {
    $installationObjects = InstallationObject::factory()
        ->count(3)
        ->create();

    $page = visit('/installation-objects')
        ->on()
        ->mobile();

    $page->assertPresent('.group\/item-group')
        ->assertCount('.group\/item', 3);

    foreach ($installationObjects as $object) {
        $page->assertSeeLink($object->name)
            ->assertSee($object->address);
    }
});

it('navigates to /installation-objects/id after clicking on an item in the list', function () {
    $installationObject = InstallationObject::factory()->create();

    $page = visit('/installation-objects')
        ->on()
        ->mobile();

    $page->click($installationObject->name)
        ->assertPathIs("/installation-objects/$installationObject->id")
        ->assertSee($installationObject->name);
});
