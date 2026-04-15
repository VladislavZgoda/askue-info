<?php

use App\Models\SimCard;

describe('SimCardController Index Page', function () {
    beforeEach(function () {
        SimCard::factory()->create(['operator' => 'МТС', 'number' => '89181111111']);
        SimCard::factory()->create(['operator' => 'Билайн', 'number' => '89182222222']);
    });

    it('displays a page with a list of sim cards', function () {
        $simCards = SimCard::all();

        $page = visit(route('sim-cards.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('sim-cards.index'))
            ->assertSeeLink('Главная')
            ->assertSeeLink('Создать сим-карту')
            ->assertValue('input[placeholder="Поиск сим-карты..."]', '')
            // Крестик, очистить поиск.
            ->assertButtonEnabled('button[type="button"]')
            ->assertPresent('.group\/item-group')
            ->assertCount('.group\/item', $simCards->count())
            ->assertNoJavaScriptErrors();

        foreach ($simCards as $simCard) {
            $page->assertSeeLink($simCard->operator)
                ->assertSee($simCard->number);
        }
    });

    it('can search for sim cards and see results update dynamically', function () {
        $page = visit(route('sim-cards.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('sim-cards.index'))
            ->assertSee('МТС')
            ->assertSee('Билайн')
            ->type('input[placeholder="Поиск сим-карты..."]', 'МТС')
            ->wait(1)
            ->assertSee('МТС')
            ->assertDontSee('Билайн')
            ->assertSee('1 шт.')
            ->assertNoJavaScriptErrors();
    });

    it('clears the search when the X button is clicked', function () {
        $page = visit(route('sim-cards.index'))
            ->on()
            ->mobile();

        $page->assertUrlIs(route('sim-cards.index'))
            ->assertSee('МТС')
            ->assertSee('Билайн')
            ->type('input[placeholder="Поиск сим-карты..."]', 'МТС')
            ->wait(1)
            ->assertDontSee('Билайн')
            // Крестик, очистить поиск.
            ->click('button[type="button"]')
            ->wait(1)
            ->assertValue('input[placeholder="Поиск сим-карты..."]', '')
            ->assertSee('МТС')
            ->assertSee('Билайн')
            ->assertNoJavaScriptErrors();
    });
});
