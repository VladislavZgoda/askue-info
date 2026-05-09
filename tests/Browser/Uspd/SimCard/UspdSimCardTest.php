<?php

use App\Models\SimCard;
use App\Models\Uspd;

it('renders form with sim cards details', function () {
    $uspd = Uspd::factory()->create();

    $url = route('uspds.sim-cards.create', $uspd);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSee("Привязать сим-карту к $uspd->model")
        ->assertSee("№$uspd->serial_number")
        ->assertSee('Сим карта')
        ->assertSelected('sim_card_id', '')
        ->assertButtonEnabled('Добавить')
        ->assertButtonEnabled('Очистить')
        ->assertNoJavaScriptErrors();
});

it('lists only filtered sim cards in the select', function () {
    $uspd = Uspd::factory()->create();
    $freeSimCard = SimCard::factory()->create();
    $uspdSimCard = SimCard::factory()->forUspd()->create();
    $meterSimCard = SimCard::factory()->hasMeters()->create();

    $url = route('uspds.sim-cards.create', $uspd);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->assertSourceHas("{$freeSimCard->operator}, {$freeSimCard->number}")
        ->assertSourceMissing("{$uspdSimCard->operator}, {$uspdSimCard->number}")
        ->assertSourceMissing("{$meterSimCard->operator}, {$meterSimCard->number}")
        ->assertNoJavaScriptErrors();
});

it('submits the form with a selected sim card, associate it and redirects', function () {
    $uspd = Uspd::factory()->create();
    $simCard = SimCard::factory()->create();

    $url = route('uspds.sim-cards.create', $uspd);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('sim_card_id', $simCard->id)
        ->click('Добавить')
        ->assertUrlIs(route('uspds.show', $uspd))
        ->assertSee('Сим-карта успешно привязана к УСПД.')
        ->assertNoJavaScriptErrors();

    $associatedSimCard = $uspd->simCards()->first();

    expect($associatedSimCard->is($simCard))->toBeTrue();
});

it('shows a validation error when submitting the form without selecting a sim card', function () {
    SimCard::factory()->create();
    $uspd = Uspd::factory()->create();

    $url = route('uspds.sim-cards.create', $uspd);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->click('Добавить')
        ->assertSee('Выберите сим-карту.')
        ->assertNoJavaScriptErrors();
});

it('resets the select back to placeholder', function () {
    $uspd = Uspd::factory()->create();
    $simCard = SimCard::factory()->create();

    $url = route('uspds.sim-cards.create', $uspd);
    $page = $this->visit($url)->on()->mobile();

    $page->assertUrlIs($url)
        ->select('sim_card_id', $simCard->id)
        ->assertSelected('sim_card_id', $simCard->id)
        ->click('Очистить')
        ->assertSelected('sim_card_id', '')
        ->assertNoJavaScriptErrors();
});

it('returns to the previous page', function () {
    $uspd = Uspd::factory()->create();

    $showUrl = route('uspds.show', $uspd);

    $page = $this->visit($showUrl)
        ->on()
        ->mobile()
        ->navigate(route('uspds.sim-cards.create', $uspd));

    $page->click('Назад')
        ->assertUrlIs($showUrl)
        ->assertNoJavaScriptErrors();
});
