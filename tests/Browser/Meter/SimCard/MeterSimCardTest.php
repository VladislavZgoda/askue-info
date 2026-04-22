<?php

use App\Models\Meter;
use App\Models\SimCard;

it('renders form with som cards details', function () {
    $meter = Meter::factory()->create();

    $url = route('meters.sim-cards.create', $meter);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("Привязать сим-карту к $meter->model")
        ->assertSee("№$meter->serial_number")
        ->assertSee('Сим карта')
        ->assertSelected('sim_card_id', '')
        ->assertButtonEnabled('Добавить')
        ->assertButtonEnabled('Очистить')
        ->assertNoJavaScriptErrors();
});

it('lists only filtered sim cards in the select', function () {
    $meter = Meter::factory()->create();
    $freeSimCard = SimCard::factory()->create();
    $uspdSimCard = SimCard::factory()->forUspd()->create();

    $url = route('meters.sim-cards.create', $meter);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSourceHas("{$freeSimCard->operator}, {$freeSimCard->number}")
        ->assertSourceMissing("{$uspdSimCard->operator}, {$uspdSimCard->number}")
        ->assertNoJavaScriptErrors();
});

it('submits the form with a selected sim card, attach it and redirects', function () {
    $meter = Meter::factory()->create();
    $simCard = SimCard::factory()->create();

    $url = route('meters.sim-cards.create', $meter);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('sim_card_id', $simCard->id)
        ->click('Добавить')
        ->assertUrlIs(route('meters.show', $meter))
        ->assertSee('Сим-карта успешно привязана к прибору учёта.')
        ->assertNoJavaScriptErrors();

    $attachedSimCard = SimCard::whereAttachedTo($meter)->first();

    expect($attachedSimCard->is($simCard))->toBeTrue();
});

it('shows a validation error when submitting the form without selecting a sim card', function () {
    SimCard::factory()->create();
    $meter = Meter::factory()->create();

    $url = route('meters.sim-cards.create', $meter);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->click('Добавить')
        ->assertSee('Выберите сим-карту.')
        ->assertNoJavaScriptErrors();
});

it('shows a validation error when the meter does not have an installation object', function () {
    $simCard = SimCard::factory()->create();

    $meter = Meter::factory()
        ->withoutInstallationObject()
        ->create();

    $url = route('meters.sim-cards.create', $meter);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('sim_card_id', $simCard->id)
        ->click('Добавить')
        ->assertSee('Прибор учёта должен иметь объект установки.')
        ->assertNoJavaScriptErrors();
});

it('resets the select back to placeholder', function () {
    $meter = Meter::factory()->create();
    $simCard = SimCard::factory()->create();

    $url = route('meters.sim-cards.create', $meter);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('sim_card_id', $simCard->id)
        ->assertSelected('sim_card_id', $simCard->id)
        ->click('Очистить')
        ->assertSelected('sim_card_id', '')
        ->assertNoJavaScriptErrors();
});

it('returns to the previous page', function () {
    $meter = Meter::factory()->create();

    $showUrl = route('meters.show', $meter);

    $page = $this->visit($showUrl)
        ->on()
        ->mobile()
        ->navigate(route('meters.sim-cards.create', $meter));

    $page->click('Назад')
        ->assertUrlIs($showUrl)
        ->assertNoJavaScriptErrors();
});
