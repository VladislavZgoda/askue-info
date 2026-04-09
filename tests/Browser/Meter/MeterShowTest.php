<?php

use App\Models\Meter;

it('renders the page with :dataset', function (Meter $meter) {
    $meter->load('simCards');

    $url = route('meters.show', $meter);
    $page = visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("$meter->model №$meter->serial_number")
        ->assertSeeLink('Просмотр приборов учёта')
        ->assertSeeLink('Добавить сим-карту')
        ->assertNoJavaScriptErrors();

    foreach ($meter->simCards as $simCard) {
        $page->assertSee($simCard->operator)
            ->assertSee($simCard->number);

        if ($simCard?->ip) {
            $page->assertSee($simCard->ip);
        }
    }
})->with([
    'one sim card' => fn () => Meter::factory()->hasSimCards(1)->create(),
    'two sim cards' => fn () => Meter::factory()->hasSimCards(2)->create(),
    'zero sim cards' => fn () => Meter::factory()->create(),
]);

it('navigates from the Show page to the Index page using the link "Просмотр приборов учёта"', function () {
    $meter = Meter::factory()->create();

    $page = visit(route('meters.show', $meter))
        ->on()
        ->mobile();

    $page->click('Просмотр приборов учёта')
        ->assertUrlIs(route('meters.index'))
        ->assertSee($meter->model)
        ->assertSee($meter->serial_number)
        ->assertNoJavaScriptErrors();
});

it('deletes the meter', function () {
    $meter = Meter::factory()->create();
    $showUrl = route('meters.show', $meter);
    $page = visit($showUrl)->on()->mobile();

    $page->assertUrlIs($showUrl)
        ->press('delete')
        ->assertSee('Удалить прибор учёта?')
        ->assertSee('Это навсегда удалит прибор учёта без возможности восстановления.')
        ->assertButtonEnabled('Отменить')
        ->assertSeeLink('Удалить')
        ->click('Удалить')
        ->assertUrlIs(route('meters.index'))
        ->assertSee('Прибор учёта успешно удалён.');

    $this->assertDatabaseMissing('meters', [
        'id' => $meter->id,
    ]);
});
