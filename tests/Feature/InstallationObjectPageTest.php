<?php

use App\Models\InstallationObject;

it('renders a page with data with :dataset', function (InstallationObject $installationObject) {
    $installationObject->load(['meters', 'uspds']);

    $page = visit(route('installation-objects.show', $installationObject))
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
})->with([
    'one meter and one uspd' => fn () => InstallationObject::factory()->hasMeters(1)->hasUspds(1)->create(),
    'two meters and two uspds' => fn () => InstallationObject::factory()->hasMeters(2)->hasUspds(2)->create(),
]);
