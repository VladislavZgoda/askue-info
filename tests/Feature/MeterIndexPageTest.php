<?php

use App\Models\Meter;

describe('MeterController Index Page', function () {
    beforeEach(function () {
        Meter::factory()->create(['model' => 'Меркурий 236', 'serial_number' => '123456']);
        Meter::factory()->create(['model' => 'СЭТ-4ТМ.03М', 'serial_number' => '654321']);
    });

    it('displays a page with a list of meters', function () {
        $meters = Meter::all();

        $page = visit(route('meters.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('meters.index'))
            ->assertSeeLink('Главная')
            ->assertSeeLink('Создать прибор учёта')
            ->assertValue('input[placeholder="Поиск приборов учёта..."]', '')
            // Крестик, очистить поиск.
            ->assertButtonEnabled('button[type="button"]')
            ->assertPresent('.group\/item-group')
            ->assertCount('.group\/item', $meters->count())
            ->assertNoJavaScriptErrors();

        foreach ($meters as $meter) {
            $page->assertSeeLink($meter->model)
                ->assertSee($meter->serial_number);
        }
    });

    it('can search for meters and see results update dynamically', function () {
        $page = visit(route('meters.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('meters.index'))
            ->assertSee('Меркурий 236')
            ->assertSee('СЭТ-4ТМ.03М')
            ->type('input[placeholder="Поиск приборов учёта..."]', 'Меркурий 236')
            ->wait(1)
            ->assertSee('Меркурий 236')
            ->assertDontSee('СЭТ-4ТМ.03М')
            ->assertSee('1 шт.')
            ->assertNoJavaScriptErrors();
    });

    it('clears the search when the X button is clicked', function () {
        $page = visit(route('meters.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('meters.index'))
            ->assertSee('Меркурий 236')
            ->assertSee('СЭТ-4ТМ.03М')
            ->type('input[placeholder="Поиск приборов учёта..."]', 'Меркурий 236')
            ->wait(1)
            ->assertDontSee('СЭТ-4ТМ.03М')
            // Крестик, очистить поиск.
            ->click('button[type="button"]')
            ->wait(1)
            ->assertValue('input[placeholder="Поиск приборов учёта..."]', '')
            ->assertSee('Меркурий 236')
            ->assertSee('СЭТ-4ТМ.03М')
            ->assertNoJavaScriptErrors();
    });
});
