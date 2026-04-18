<?php

use App\Models\SimCard;

it('renders the page', function () {
    $createUrl = route('sim-cards.create');
    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->assertSourceHas('<form action="/sim-cards" method="post">')
        ->assertSee('Добавить сим-карту')
        ->assertSee('Оператор')
        ->assertSee('Номер')
        ->assertSee('IP адрес')
        ->assertSelected('operator', '')
        ->assertValue('input[name=number]', '')
        ->assertValue('input[name=ip]', '')
        ->assertButtonEnabled('Создать')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $createUrl = route('sim-cards.create');
    $page = visit($createUrl)->on()->mobile();

    $simCard = SimCard::factory()
        ->make(['ip' => '192.168.3.1'])
        ->toArray();

    $page->assertUrlIs($createUrl)
        ->select('operator', $simCard['operator'])
        ->type('number', $simCard['number'])
        ->type('ip', $simCard['ip'])
        ->pressAndWaitFor('Создать', 2)
        ->assertUrlIs(route('sim-cards.show', 1))
        ->assertSee('Сим-карта успешно создана.')
        ->assertSee($simCard['operator'])
        ->assertSee($simCard['number'])
        ->assertSee($simCard['ip'])
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $createUrl = route('sim-cards.create');

    $simCard = SimCard::factory()
        ->make(['ip' => '192.168.3.1'])
        ->toArray();

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->select('operator', $simCard['operator'])
        ->type('number', $simCard['number'])
        ->type('ip', $simCard['ip'])
        ->assertSelected('operator', $simCard['operator'])
        ->assertValue('input[name=number]', $simCard['number'])
        ->assertValue('input[name=ip]', $simCard['ip'])
        ->pressAndWaitFor('Очистить', 2)
        ->assertSelected('operator', '')
        ->assertValue('input[name=number]', '')
        ->assertValue('input[name=ip]', '')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $simCard = SimCard::factory()->create(['ip' => '192.168.3.1']);
    $createUrl = route('sim-cards.create');

    visit($createUrl)
        ->on()
        ->mobile()
        ->assertUrlIs($createUrl)
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Поле "Оператор" является обязательным.')
        ->assertSee('Поле "Номер" является обязательным.')
        ->type('number', $simCard->number)
        ->type('ip', $simCard->ip)
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Номер уже используется.')
        ->assertSee('Ip уже используется.')
        ->pressAndWaitFor('Очистить', 1)
        ->type('number', '7932487878878')
        ->type('ip', '192.2.3.1000')
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Формат поля номер недопустим.')
        ->assertSee('В поле ip должен быть указан действительный IPv4-адрес.')
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $indexUrl = route('sim-cards.index');
    $page = visit($indexUrl)->on()->mobile();

    $page->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать сим-карту')
        ->click('Создать сим-карту')
        ->assertUrlIs(route('sim-cards.create'))
        ->assertSee('Добавить сим-карту')
        ->pressAndWaitFor('Назад', 2)
        ->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать сим-карту')
        ->assertNoJavaScriptErrors();
});
