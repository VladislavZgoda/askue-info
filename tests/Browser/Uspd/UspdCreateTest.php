<?php

use App\Models\Uspd;

it('renders the page', function () {
    $createUrl = route('uspds.create');
    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->assertSourceHas('<form action="/uspds" method="post">')
        ->assertSee('Создать УСПД')
        ->assertSee('Модель')
        ->assertSee('Серийный номер')
        ->assertSee('Lan IP')
        ->assertSelected('model', '')
        ->assertValue('input[name=serial_number]', '')
        ->assertValue('input[name=lan_ip]', '192.168.0.100')
        ->assertButtonEnabled('Создать')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $createUrl = route('uspds.create');
    $page = visit($createUrl)->on()->mobile();

    $uspd = Uspd::factory()
        ->make(['lan_ip' => '192.168.3.1'])
        ->toArray();

    $page->assertUrlIs($createUrl)
        ->select('model', $uspd['model'])
        ->type('serial_number', strval($uspd['serial_number']))
        ->type('lan_ip', $uspd['lan_ip'])
        ->pressAndWaitFor('Создать', 2)
        ->assertUrlIs(route('uspds.show', 1))
        ->assertSee('УСПД успешно создан.')
        ->assertSee($uspd['model'])
        ->assertSee($uspd['serial_number'])
        ->assertSee($uspd['lan_ip'])
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $createUrl = route('uspds.create');

    $uspd = Uspd::factory()
        ->make(['lan_ip' => '192.168.3.1'])
        ->toArray();

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->select('model', $uspd['model'])
        ->type('serial_number', strval($uspd['serial_number']))
        ->type('lan_ip', $uspd['lan_ip'])
        ->assertSelected('model', $uspd['model'])
        ->assertValue('input[name=serial_number]', $uspd['serial_number'])
        ->assertValue('input[name=lan_ip]', $uspd['lan_ip'])
        ->pressAndWaitFor('Очистить', 2)
        ->assertSelected('model', '')
        ->assertValue('input[name=serial_number]', '')
        ->assertValue('input[name=lan_ip]', '192.168.0.100')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $uspd = Uspd::factory()->create(['lan_ip' => '10.172.10.19']);
    $createUrl = route('uspds.create');

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->pressAndWaitFor('Создать', 1)
        ->assertSee('Поле "Модель" является обязательным.')
        ->assertSee('Поле "Серийный номер" является обязательным.')
        ->pressAndWaitFor('Очистить', 1)
        ->select('model', $uspd->model)
        ->type('serial_number', strval($uspd->serial_number))
        ->type('lan_ip', $uspd->lan_ip)
        ->pressAndWaitFor('Создать', 1)
        ->assertSee('Серийный номер уже используется.')
        ->assertSee('Lan IP уже используется.')
        ->pressAndWaitFor('Очистить', 1)
        ->select('model', $uspd->model)
        ->type('serial_number', '11223344')
        ->type('lan_ip', '192.160.0.300')
        ->pressAndWaitFor('Создать', 1)
        ->assertSee('Поле серийный номер должно состоять из 7 цифр.')
        ->assertSee('В поле Lan IP должен быть указан действительный IPv4-адрес.')
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $indexUrl = route('uspds.index');
    $page = visit($indexUrl)->on()->mobile();

    $page->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать УСПД')
        ->click('Создать УСПД')
        ->assertUrlIs(route('uspds.create'))
        ->assertSee('Создать УСПД')
        ->pressAndWaitFor('Назад', 1)
        ->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать УСПД')
        ->assertNoJavaScriptErrors();
});
