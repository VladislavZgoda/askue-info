<?php

use App\Models\Uspd;

beforeEach(function () {
    $this->uspd = Uspd::factory()->create([
        'model' => 'RTR8A.LGE-2-2-RUF',
        'lan_ip' => '192.168.3.1',
    ]);
});

it('renders the page with data', function () {
    $editUrl = route('uspds.edit', $this->uspd);
    $page = visit($editUrl)->on()->mobile();

    $uspdId = $this->uspd->id;

    $page->assertUrlIs($editUrl)
        ->assertSourceHas("<form action=\"/uspds/$uspdId\" method=\"put\">")
        ->assertSee('Редактировать УСПД')
        ->assertSee('Модель')
        ->assertSee('Серийный номер')
        ->assertSee('Lan IP')
        ->assertSelected('model', $this->uspd->model)
        ->assertValue('input[name=serial_number]', $this->uspd->serial_number)
        ->assertValue('input[name=lan_ip]', $this->uspd->lan_ip)
        ->assertButtonEnabled('Изменить')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $uspd2 = Uspd::factory()->create(['lan_ip' => '192.168.2.2']);
    $editUrl = route('uspds.edit', $this->uspd);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->clear('serial_number')
        ->pressAndWaitFor('Изменить', 1)
        ->assertSee('Поле "Серийный номер" является обязательным.')
        ->pressAndWaitFor('Очистить', 1)
        ->type('serial_number', strval($uspd2->serial_number))
        ->type('lan_ip', $uspd2->lan_ip)
        ->pressAndWaitFor('Изменить', 1)
        ->assertSee('Серийный номер уже используется.')
        ->assertSee('Lan IP уже используется.')
        ->pressAndWaitFor('Очистить', 1)
        ->type('serial_number', '11223344')
        ->type('lan_ip', '192.160.0.300')
        ->pressAndWaitFor('Изменить', 1)
        ->assertSee('Поле серийный номер должно состоять из 7 цифр.')
        ->assertSee('В поле Lan IP должен быть указан действительный IPv4-адрес.')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $editUrl = route('uspds.edit', $this->uspd);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->select('model', 'RTR58A.LG-1-1')
        ->type('lan_ip', '192.168.2.2')
        ->pressAndWaitFor('Изменить', 1)
        ->assertUrlIs(route('uspds.show', $this->uspd))
        ->assertSee('УСПД успешно обновлён.')
        ->assertSee('RTR58A.LG-1-1')
        ->assertSee('192.168.2.2')
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $editUrl = route('uspds.edit', $this->uspd);
    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->select('model', 'RTR58A.LG-1-1')
        ->clear('serial_number')
        ->type('serial_number', '1223334')
        ->clear('lan_ip')
        ->type('lan_ip', '192.168.3.3')
        ->assertSelected('model', 'RTR58A.LG-1-1')
        ->assertValue('input[name=serial_number]', '1223334')
        ->assertValue('input[name=lan_ip]', '192.168.3.3')
        ->pressAndWaitFor('Очистить', 1)
        ->assertSelected('model', $this->uspd->model)
        ->assertValue('input[name=serial_number]', $this->uspd->serial_number)
        ->assertValue('input[name=lan_ip]', $this->uspd->lan_ip)
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $showUrl = route('uspds.show', $this->uspd);
    $page = visit($showUrl)->on()->mobile();

    $uspdId = $this->uspd->id;

    $page->assertUrlIs($showUrl)
        ->assertSee($this->uspd->serial_number)
        ->click("a[href=\"/uspds/$uspdId/edit\"]")
        ->assertUrlIs(route('uspds.edit', $this->uspd))
        ->assertSee('Редактировать УСПД')
        ->pressAndWaitFor('Назад', 1)
        ->assertUrlIs($showUrl)
        ->assertSee($this->uspd->serial_number)
        ->assertNoJavaScriptErrors();
});
