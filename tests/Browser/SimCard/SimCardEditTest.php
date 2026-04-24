<?php

use App\Models\SimCard;

beforeEach(function () {
    $this->simCard = SimCard::factory()->create(['ip' => '192.168.1.1']);
});

it('renders the page with data', function () {
    $editUrl = route('sim-cards.edit', $this->simCard);
    $page = visit($editUrl)->on()->mobile();

    $simCardId = $this->simCard->id;

    $page->assertUrlIs($editUrl)
        ->assertSourceHas("<form action=\"/sim-cards/$simCardId\" method=\"put\">")
        ->assertSee('Редактировать сим-карту')
        ->assertSee('Оператор')
        ->assertSee('Номер')
        ->assertSee('Ip адрес')
        ->assertSelected('operator', $this->simCard->operator)
        ->assertValue('input[name=number]', $this->simCard->number)
        ->assertValue('input[name=ip]', $this->simCard->ip)
        ->assertButtonEnabled('Изменить')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $simCard2 = SimCard::factory()->create(['ip' => '192.168.2.2']);
    $editUrl = route('sim-cards.edit', $this->simCard);

    visit($editUrl)
        ->on()
        ->mobile()
        ->assertUrlIs($editUrl)
        ->clear('number')
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Поле "Номер" является обязательным.')
        ->select('operator', $this->simCard->operator)
        ->type('number', $simCard2->number)
        ->type('ip', $simCard2->ip)
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Номер уже используется.')
        ->assertSee('Ip уже используется.')
        ->pressAndWaitFor('Очистить', 1)
        ->type('number', '7932487878878')
        ->type('ip', '192.2.3.1000')
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Формат поля номер недопустим.')
        ->assertSee('В поле ip должен быть указан действительный IPv4-адрес.')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $editUrl = route('sim-cards.edit', $this->simCard);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->type('ip', '192.168.2.2')
        ->pressAndWaitFor('Изменить', 2)
        ->assertUrlIs(route('sim-cards.show', $this->simCard))
        ->assertSee('Сим-карта успешно обновлена.')
        ->assertSee('192.168.2.2')
        ->assertNoJavaScriptErrors();
});
