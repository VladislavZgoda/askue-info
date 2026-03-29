<?php

use App\Models\InstallationObject;

describe('InstallationObjectController Index Page', function () {
    beforeEach(function () {
        InstallationObject::factory()->create(['name' => 'ТП-111', 'address' => 'ул. Красная, 1']);
        InstallationObject::factory()->create(['name' => 'ТП-222', 'address' => 'ул. Белая, 2']);
    });

    it('displays a page with a list of installation objects', function () {
        $installationObjects = InstallationObject::all();

        $page = visit(route('installation-objects.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('installation-objects.index'))
            ->assertSeeLink('Главная')
            ->assertSeeLink('Создать объект установки')
            ->assertValue('input[placeholder="Поиск объекта установки..."]', '')
            // Крестик, очистить поиск.
            ->assertButtonEnabled('button[type="button"]')
            ->assertPresent('.group\/item-group')
            ->assertCount('.group\/item', $installationObjects->count())
            ->assertNoJavaScriptErrors();

        foreach ($installationObjects as $object) {
            $page->assertSeeLink($object->name)
                ->assertSee($object->address);
        }
    });

    it('can search for installation objects and see results update dynamically', function () {
        $page = visit(route('installation-objects.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('installation-objects.index'))
            ->assertSee('ТП-111')
            ->assertSee('ТП-222')
            ->type('input[placeholder="Поиск объекта установки..."]', 'ТП-111')
            ->wait(1)
            ->assertSee('ТП-111')
            ->assertDontSee('ТП-222')
            ->assertSee('1 шт.')
            ->assertNoJavaScriptErrors();
    });

    it('clears the search when the X button is clicked', function () {
        $page = visit(route('installation-objects.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('installation-objects.index'))
            ->assertSee('ТП-111')
            ->assertSee('ТП-222')
            ->type('input[placeholder="Поиск объекта установки..."]', 'ТП-111')
            ->wait(1)
            ->assertDontSee('ТП-222')
            // Крестик, очистить поиск.
            ->click('button[type="button"]')
            ->wait(1)
            ->assertValue('input[placeholder="Поиск объекта установки..."]', '')
            ->assertSee('ТП-111')
            ->assertSee('ТП-222')
            ->assertNoJavaScriptErrors();
    });

    it('navigates to installation-objects.show after clicking on an item in the list', function () {
        $installationObject = InstallationObject::first();

        $page = visit(route('installation-objects.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('installation-objects.index'))
            ->click($installationObject->name)
            ->assertUrlIs(route('installation-objects.show', $installationObject))
            ->assertSee($installationObject->name)
            ->assertNoJavaScriptErrors();
    });

    it('navigates to installation-objects.create after clicking on the link', function () {
        $page = visit(route('installation-objects.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('installation-objects.index'))
            ->click('Создать объект установки')
            ->assertUrlIs(route('installation-objects.create'))
            ->assertSee('Создать объект установки')
            ->assertNoJavaScriptErrors();
    });
});
