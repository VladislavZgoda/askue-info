<?php

use App\Models\Uspd;

it('renders the page :dataset', function (Uspd $uspd) {
    $url = route('uspds.show', $uspd);
    $page = visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("$uspd->model, №$uspd->serial_number")
        ->assertSee("Lan IP: $uspd->lan_ip")
        ->assertSeeLink('Просмотр УСПД')
        ->assertSeeLink('Добавить сим-карту')
        ->assertNoJavaScriptErrors();

    if ($uspd->sim_cards_count > 0) {
        foreach ($uspd->simCards as $simCard) {
            $page->assertSee("$simCard->operator, $simCard->number");
        }
    }

    if ($uspd->installationObject) {
        $page->assertSee('Место установки:')
            ->assertSee($uspd->installationObject->name.', '.$uspd->installationObject->address);
    }
})->with([
    'without sim cards and installation object' => fn () => Uspd::factory()->withoutInstallationObject()->create(),
    'with sim cards' => fn () => Uspd::factory()->withoutInstallationObject()->hasSimCards(2)->create(),
    'with installation object' => fn () => Uspd::factory()->create(),
    'with sim cards and installation object' => fn () => Uspd::factory()->hasSimCards(2)->create(),
]);

it('navigates to the sim-cards.show page', function () {
    $uspd = Uspd::factory()->hasSimCards()->create();
    $simCard = $uspd->simCards()->first();

    $page = visit(route('uspds.show', $uspd))
        ->on()
        ->mobile();

    $page->click("a[href=\"/sim-cards/$simCard->id\"]")
        ->assertUrlIs(route('sim-cards.show', $simCard))
        ->assertSee($simCard->operator)
        ->assertSee($simCard->number)
        ->assertNoJavaScriptErrors();
});

it('navigates from the Show page to the Index page using the link "Просмотр УСПД"', function () {
    $uspd = Uspd::factory()->create();

    $page = visit(route('uspds.show', $uspd))
        ->on()
        ->mobile();

    $page->click('Просмотр УСПД')
        ->assertUrlIs(route('uspds.index'))
        ->assertSee($uspd->model)
        ->assertSee($uspd->serial_number)
        ->assertNoJavaScriptErrors();
});

it('deletes the uspd', function () {
    $uspd = Uspd::factory()->create();
    $showUrl = route('uspds.show', $uspd);
    $page = visit($showUrl)->on()->mobile();

    $page->assertUrlIs($showUrl)
        ->press('delete')
        ->assertSee('Удалить УСПД?')
        ->assertSee('Это навсегда удалит УСПД без возможности восстановления.')
        ->assertButtonEnabled('Отменить')
        ->assertSeeLink('Удалить')
        ->click('Удалить')
        ->assertUrlIs(route('uspds.index'))
        ->assertSee('УСПД успешно удалён.')
        ->assertNoJavaScriptErrors();

    $this->assertDatabaseMissing('uspds', [
        'id' => $uspd->id,
    ]);
});

it('navigates from the Show page to the Edit using the link :dataset', function () {
    $uspd = Uspd::factory()->create();

    $page = visit(route('uspds.show', $uspd))
        ->on()
        ->mobile();

    $page->click("a[href=\"/uspds/$uspd->id/edit\"]")
        ->assertUrlIs(route('uspds.edit', $uspd))
        ->assertSee('Редактировать УСПД')
        ->assertSee('Серийный номер')
        ->assertSee('Lan IP')
        ->assertSelected('model', $uspd->model)
        ->assertValue('input[name=serial_number]', $uspd->serial_number)
        ->assertValue('input[name=lan_ip]', $uspd->lan_ip)
        ->assertNoJavaScriptErrors();
});
