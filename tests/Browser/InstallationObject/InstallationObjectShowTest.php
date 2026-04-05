<?php

use App\Models\InstallationObject;
use App\Models\Meter;

it('renders a page with data with :dataset', function (InstallationObject $installationObject) {
    $installationObject->load(['meters', 'uspds']);

    $page = visit(route('installation-objects.show', $installationObject))
        ->on()
        ->mobile();

    $page->assertSee($installationObject->name)
        ->assertSourceHas("href=\"/installation-objects/$installationObject->id/edit\"")
        ->assertButtonEnabled('delete')
        ->assertSeeLink('Список объектов установки')
        ->assertSeeLink('Добавить УСПД')
        ->assertSeeLink('Добавить ПУ')
        ->assertCount('.group\/item-group', 2)
        ->assertNoJavaScriptErrors();

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
    'two meters and two uspds' => fn () => InstallationObject::factory()->hasMeters(2)->hasUspds(2)->create(['id' => 5]),
]);

it('navigates from the Show page to the Edit using the link :dataset', function (InstallationObject $installationObject) {
    $page = visit(route('installation-objects.show', $installationObject))
        ->on()
        ->mobile();

    $page->click("a[href=\"/installation-objects/$installationObject->id/edit\"]")
        ->assertUrlIs(route('installation-objects.edit', [$installationObject]))
        ->assertSee('Редактировать объект установки')
        ->assertSee('Наименование')
        ->assertSee('Адрес')
        ->assertValue('input[name=name]', $installationObject->name)
        ->assertValue('input[name=address]', $installationObject->address);
})->with([
    'ТП-1' => fn () => InstallationObject::factory()->create(['name' => 'ТП-1']),
    'ТП-2' => fn () => InstallationObject::factory()->create(['id' => 10, 'name' => 'ТП-2']),
]);

it('navigates from the Show page to the Index page using the link "Список объектов установки"', function () {
    $installationObject = InstallationObject::factory()->create();

    $page = visit(route('installation-objects.show', [$installationObject]))
        ->on()
        ->mobile();

    $page->click('Список объектов установки')
        ->assertUrlIs(route('installation-objects.index'))
        ->assertSee($installationObject->name)
        ->assertSee($installationObject->address);
});

it('can delete :dataset', function (InstallationObject $installationObject) {
    $showUrl = route('installation-objects.show', [$installationObject]);

    $page = visit($showUrl)->on()->mobile();

    $page->assertUrlIs($showUrl)
        ->press('delete')
        ->assertSee('Удалить объект установки?')
        ->assertSee('Это навсегда удалит объект установки без возможности восстановления.')
        ->assertButtonEnabled('Отменить')
        ->assertSeeLink('Удалить')
        ->click('Удалить')
        ->assertUrlIs(route('installation-objects.index'))
        ->assertSee('Объект установки успешно удалён.');

    $this->assertDatabaseMissing('installation_objects', [
        'id' => $installationObject->id,
    ]);
})->with([
    'ТП-1' => fn () => InstallationObject::factory()->create(['name' => 'ТП-1']),
    'ТП-2' => fn () => InstallationObject::factory()->create(['name' => 'ТП-2']),
]);

it('can disassociate the meter', function () {
    $installationObject = InstallationObject::factory()->create();
    $meter = Meter::factory()->create(['installation_object_id' => $installationObject->id]);

    $url = route('installation-objects.show', [$installationObject]);
    $page = visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->press('unplugMeter')
        ->assertSee('Отсоединить прибор учёта?')
        ->assertSee('Это не удалит прибор учёта и его можно будет присоединить к любому объекту.')
        ->assertButtonEnabled('Отменить')
        ->assertSeeLink('Отсоединить')
        ->click('Отсоединить')
        ->assertUrlIs($url)
        ->assertSee('Прибор учёта успешно отсоединился.');

    expect($meter->fresh()->installation_object_id)->toBeNull();
});
