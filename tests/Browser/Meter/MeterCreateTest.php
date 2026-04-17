<?php

use App\Models\Meter;
use Illuminate\Support\Str;

it('renders the page', function () {
    $createUrl = route('meters.create');
    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->assertSourceHas('<form action="/meters" method="post">')
        ->assertSee('Создать прибор учёта')
        ->assertSee('Наименование модели')
        ->assertSee('Серийный номер')
        ->assertValue('input[name=model]', '')
        ->assertValue('input[name=serial_number]', '')
        ->assertButtonEnabled('Создать')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertSee('Введите уникальный серийный номер.')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $createUrl = route('meters.create');
    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->typeSlowly('model', 'Меркурий 234')
        ->typeSlowly('serial_number', '111111111')
        ->pressAndWaitFor('Создать', 2)
        ->assertUrlIs(route('meters.show', 1))
        ->assertSee('Прибор учёта успешно создан.')
        ->assertSee('Меркурий 234, №111111111')
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $createUrl = route('meters.create');
    $model = Str::random();
    $serial_number = Str::repeat('1', 10);

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->type('model', $model)
        ->type('serial_number', $serial_number)
        ->assertValue('input[name=model]', $model)
        ->assertValue('input[name=serial_number]', $serial_number)
        ->pressAndWaitFor('Очистить', 2)
        ->assertValue('input[name=model]', '')
        ->assertValue('input[name=serial_number]', '')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $meter = Meter::factory()->create();

    $createUrl = route('meters.create');

    visit($createUrl)
        ->on()
        ->mobile()
        ->assertUrlIs($createUrl)
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Поле "Модель" является обязательным.')
        ->assertSee('Поле "Серийный номер" является обязательным.')
        ->type('model', $meter->model)
        ->type('serial_number', $meter->serial_number)
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Серийный номер уже используется.')
        ->type('model', Str::random(256))
        ->type('serial_number', Str::repeat('1', 256))
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Поле "Модель" не должно превышать значение 255 символов.')
        ->assertSee('Поле "Серийный номер" не должно превышать значение 255 символов.')
        ->pressAndWaitFor('Очистить', 2)
        ->type('serial_number', Str::random())
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Формат поля серийный номер недопустим.')
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $indexUrl = route('meters.index');
    $page = visit($indexUrl)->on()->mobile();

    $page->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать прибор учёта')
        ->click('Создать прибор учёта')
        ->assertUrlIs(route('meters.create'))
        ->assertSee('Создать прибор учёта')
        ->pressAndWaitFor('Назад', 2)
        ->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать прибор учёта')
        ->assertNoJavaScriptErrors();
});
