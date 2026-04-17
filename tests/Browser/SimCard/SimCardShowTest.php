<?php

use App\Models\SimCard;

it('renders the page with :dataset', function (SimCard $simCard) {
    $simCard->loadExists(['meters', 'uspd']);

    $url = route('sim-cards.show', $simCard);
    $page = visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("$simCard->operator, $simCard->number")
        ->assertSeeLink('Просмотр сим-карт')
        ->assertSeeLink('Связать с УСПД')
        ->assertSeeLink('Связать с ПУ')
        ->assertNoJavaScriptErrors();

    if ($simCard?->ip) {
        $page->assertSee($simCard->ip);
    }

    if ($simCard->meters_exists) {
        $page->assertSee('Относится к следующим ПУ:');

        foreach ($simCard->meters as $meter) {
            $page->assertSee("$meter->model, №$meter->serial_number");
        }
    }

    if ($simCard->uspd_exists) {
        $page->assertSee('Относится к УСПД:')
            ->assertSee($simCard->uspd->model.', №'.$simCard->uspd->serial_number);
    }
})->with([
    'attached meter' => fn () => SimCard::factory()->hasMeters()->create(),
    'associated uspd' => fn () => SimCard::factory()->forUspd()->create(),
]);

it('navigates to the meters.show page', function () {
    $simCard = SimCard::factory()->hasMeters()->create();
    $meter = $simCard->meters()->first();

    $page = visit(route('sim-cards.show', $simCard))
        ->on()
        ->mobile();

    $page->click("a[href=\"/meters/$meter->id\"]")
        ->assertUrlIs(route('meters.show', $meter))
        ->assertSee($meter->model)
        ->assertSee($meter->serial_number)
        ->assertNoJavaScriptErrors();
});

it('navigates from the Show page to the Index page using the link "Просмотр сим-карт"', function () {
    $simCard = SimCard::factory()->create();

    $page = visit(route('sim-cards.show', $simCard))
        ->on()
        ->mobile();

    $page->click('Просмотр сим-карт')
        ->assertUrlIs(route('sim-cards.index'))
        ->assertSee($simCard->operator)
        ->assertSee($simCard->number)
        ->assertNoJavaScriptErrors();
});

it('deletes the sim card', function () {
    $simCard = SimCard::factory()->create();
    $showUrl = route('sim-cards.show', $simCard);
    $page = visit($showUrl)->on()->mobile();

    $page->assertUrlIs($showUrl)
        ->press('delete')
        ->assertSee('Удалить сим-карту?')
        ->assertSee('Это навсегда удалит сим-карту без возможности восстановления.')
        ->assertButtonEnabled('Отменить')
        ->assertSeeLink('Удалить')
        ->click('Удалить')
        ->assertUrlIs(route('sim-cards.index'))
        ->assertSee('Сим-карта успешно удалена.')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseMissing('sim_cards', [
        'id' => $simCard->id,
    ]);
});
