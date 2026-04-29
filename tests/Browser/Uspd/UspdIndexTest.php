<?php

use App\Models\Uspd;

describe('UspdController Index Page', function () {
    beforeEach(function () {
        Uspd::factory()->create(['serial_number' => '4784312']);
        Uspd::factory()->create(['serial_number' => '3865434']);
    });

    it('displays a page with a list of uspds', function () {
        $uspds = Uspd::all();

        $page = visit(route('uspds.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('uspds.index'))
            ->assertSeeLink('Главная')
            ->assertSeeLink('Создать УСПД')
            ->assertValue('input[placeholder="Поиск УСПД..."]', '')
            // Крестик, очистить поиск.
            ->assertButtonEnabled('button[type="button"]')
            ->assertCount('.group\/item', $uspds->count())
            ->assertNoJavaScriptErrors();

        foreach ($uspds as $uspd) {
            $page->assertSeeLink("$uspd->serial_number")
                ->assertSee($uspd->model);
        }
    });

    it('can search for uspds and see results update dynamically', function () {
        $page = visit(route('uspds.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('uspds.index'))
            ->assertSee('4784312')
            ->assertSee('3865434')
            ->type('input[placeholder="Поиск УСПД..."]', '4784312')
            ->wait(1)
            ->assertSee('4784312')
            ->assertDontSee('3865434')
            ->assertSee('1 шт.')
            ->assertNoJavaScriptErrors();
    });

    it('clears the search when the X button is clicked', function () {
        $page = visit(route('uspds.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('uspds.index'))
            ->assertSee('4784312')
            ->assertSee('3865434')
            ->type('input[placeholder="Поиск УСПД..."]', '4784312')
            ->wait(1)
            ->assertDontSee('3865434')
            // Крестик, очистить поиск.
            ->click('button[type="button"]')
            ->wait(1)
            ->assertValue('input[placeholder="Поиск УСПД..."]', '')
            ->assertSee('4784312')
            ->assertSee('3865434')
            ->assertNoJavaScriptErrors();
    });
});
