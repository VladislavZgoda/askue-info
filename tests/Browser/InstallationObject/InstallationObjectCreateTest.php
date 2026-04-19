<?php

use App\Models\InstallationObject;
use Illuminate\Support\Str;

it('renders the page', function () {
    $createUrl = route('installation-objects.create');

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->assertSourceHas('<form action="/installation-objects" method="post">')
        ->assertSee('Создать объект установки')
        ->assertSee('Наименование')
        ->assertSee('Адрес')
        ->assertValue('input[name=name]', '')
        ->assertValue('input[name=address]', '')
        ->assertButtonEnabled('Создать')
        ->assertButtonEnabled('Очистить')
        ->assertButtonEnabled('Назад')
        ->assertSee('Выберете уникальное наименование.')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $installationObject = InstallationObject::factory()->create();
    $createUrl = route('installation-objects.create');

    visit($createUrl)
        ->on()
        ->mobile()
        ->assertUrlIs($createUrl)
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Поле "Наименование" является обязательным.')
        ->assertSee('Поле "Адрес" является обязательным.')
        ->type('name', $installationObject->name)
        ->type('address', $installationObject->address)
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Наименование уже используется.')
        ->pressAndWaitFor('Очистить', 1)
        ->type('name', Str::random(256))
        ->type('address', Str::random(256))
        ->pressAndWaitFor('Создать', 2)
        ->assertSee('Поле "Наименование" не должно превышать значение 255 символов.')
        ->assertSee('Поле "Адрес" не должно превышать значение 255 символов.')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $createUrl = route('installation-objects.create');

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->typeSlowly('name', 'ТП-2')
        ->typeSlowly('address', 'ул. Уличная, 5')
        ->pressAndWaitFor('Создать', 2)
        ->assertUrlIs(route('installation-objects.show', 1))
        ->assertSee('Объект установки успешно создан.')
        ->assertSee('ТП-2')
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $createUrl = route('installation-objects.create');
    $name = Str::random();
    $address = Str::random();

    $page = visit($createUrl)->on()->mobile();

    $page->assertUrlIs($createUrl)
        ->type('name', $name)
        ->type('address', $address)
        ->assertValue('input[name=name]', $name)
        ->assertValue('input[name=address]', $address)
        ->pressAndWaitFor('Очистить', 2)
        ->assertValue('input[name=name]', '')
        ->assertValue('input[name=address]', '')
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $indexUrl = route('installation-objects.index');

    $page = visit($indexUrl)->on()->mobile();

    $page->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать объект установки')
        ->click('Создать объект установки')
        ->assertUrlIs(route('installation-objects.create'))
        ->assertSee('Создать объект установки')
        ->pressAndWaitFor('Назад', 2)
        ->assertUrlIs($indexUrl)
        ->assertSeeLink('Создать объект установки')
        ->assertNoJavaScriptErrors();
});
