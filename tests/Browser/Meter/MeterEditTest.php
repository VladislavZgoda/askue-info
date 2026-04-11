<?php

use App\Models\Meter;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->meter = Meter::factory()->create();
});

it('renders the page with data', function () {
    $editUrl = route('meters.edit', $this->meter);
    $page = visit($editUrl)->on()->mobile();

    $meterId = $this->meter->id;

    $page->assertUrlIs($editUrl)
        ->assertSourceHas("<form action=\"/meters/$meterId\" method=\"put\">")
        ->assertSee('Редактировать прибор учёта')
        ->assertSee('Наименование модели')
        ->assertSee('Серийный номер')
        ->assertValue('input[name=model]', $this->meter->model)
        ->assertValue('input[name=serial_number]', $this->meter->serial_number)
        ->assertButtonEnabled('Изменить')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertSee('Введите уникальный серийный номер.')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $otherMeter = Meter::factory()->create();

    $editUrlMeter = route('meters.edit', $this->meter);

    visit($editUrlMeter)
        ->on()
        ->mobile()
        ->assertUrlIs($editUrlMeter)
        ->clear('model')
        ->clear('serial_number')
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Поле "Модель" является обязательным.')
        ->assertSee('Поле "Серийный номер" является обязательным.')
        ->type('model', $this->meter->model)
        ->type('serial_number', $otherMeter->serial_number)
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Серийный номер уже используется.')
        ->type('model', Str::random(256))
        ->type('serial_number', Str::repeat('1', 256))
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Поле "Модель" не должно превышать значение 255 символов.')
        ->assertSee('Поле "Серийный номер" не должно превышать значение 255 символов.')
        ->pressAndWaitFor('Очистить', 2)
        ->type('model', $this->meter->model)
        ->type('serial_number', '123f456')
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Формат поля серийный номер недопустим.')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $meter = Meter::factory()->create(['model' => 'Меркурий 236']);

    $editUrl = route('meters.edit', $meter);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->typeSlowly('model', 'Меркурий 234')
        ->pressAndWaitFor('Изменить', 2)
        ->assertUrlIs(route('meters.show', $meter))
        ->assertSee('Данные успешно обновлены.')
        ->assertSee('Меркурий 234')
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $editUrl = route('meters.edit', $this->meter);
    $newModel = Str::random();
    $newSerialNumber = Str::repeat('1', 10);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->clear('model')
        ->type('model', $newModel)
        ->clear('serial_number')
        ->type('serial_number', $newSerialNumber)
        ->assertValue('input[name=model]', $newModel)
        ->assertValue('input[name=serial_number]', $newSerialNumber)
        ->pressAndWaitFor('Очистить', 2)
        ->assertValue('input[name=model]', $this->meter->model)
        ->assertValue('input[name=serial_number]', $this->meter->serial_number)
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $showUrl = route('meters.show', $this->meter);
    $page = visit($showUrl)->on()->mobile();

    $meterId = $this->meter->id;

    $page->assertUrlIs($showUrl)
        ->assertSee($this->meter->serial_number)
        ->click("a[href=\"/meters/$meterId/edit\"]")
        ->assertUrlIs(route('meters.edit', $this->meter))
        ->assertSee('Редактировать прибор учёта')
        ->pressAndWaitFor('Назад', 2)
        ->assertUrlIs($showUrl)
        ->assertSee($this->meter->serial_number)
        ->assertNoJavaScriptErrors();
});
