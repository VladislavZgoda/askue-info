<?php

use App\Models\InstallationObject;

it('shows the list of installation objects', function () {
    $installationObjects = InstallationObject::factory()
        ->count(3)
        ->create();

    $page = visit(route('installation-objects.index'))
        ->on()
        ->mobile();

    $page->assertPresent('.group\/item-group')
        ->assertCount('.group\/item', $installationObjects->count());

    foreach ($installationObjects as $object) {
        $page->assertSeeLink($object->name)
            ->assertSee($object->address);
    }
});

it('navigates to installation-objects.show after clicking on an item in the list', function () {
    $installationObject = InstallationObject::factory()->create();

    $page = visit(route('installation-objects.index'))
        ->on()
        ->mobile();

    $page->click($installationObject->name)
        ->assertUrlIs(route('installation-objects.show', $installationObject))
        ->assertSee($installationObject->name);
});
