<?php

use App\Models\InstallationObject;
use Illuminate\Support\Str;

it('renders the page with data', function () {
    $installationObject = InstallationObject::factory()->create();

    $editUrl = route('installation-objects.edit', $installationObject);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->assertSourceHas("<form action=\"/installation-objects/$installationObject->id\" method=\"put\">")
        ->assertSee('Редактировать объект установки')
        ->assertSee('Наименование')
        ->assertSee('Адрес')
        ->assertValue('input[name=name]', $installationObject->name)
        ->assertValue('input[name=address]', $installationObject->address)
        ->assertButtonEnabled('Изменить')
        ->assertButtonEnabled('Сбросить')
        ->assertButtonEnabled('Назад')
        ->assertSeeLink('Список объектов установки')
        ->assertSee('Выберете уникальное наименование.')
        ->assertNoJavaScriptErrors();
});

it('displays validation errors', function () {
    $installationObject1 = InstallationObject::factory()->create();
    $installationObject2 = InstallationObject::factory()->create();

    $editUrlObject1 = route('installation-objects.edit', [$installationObject1]);

    visit($editUrlObject1)
        ->on()
        ->mobile()
        ->assertUrlIs($editUrlObject1)
        ->clear('name')
        ->clear('address')
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Поле "Наименование" является обязательным.')
        ->assertSee('Поле "Адрес" является обязательным.')
        ->type('name', $installationObject2->name)
        ->type('address', $installationObject1->address)
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Наименование уже используется.')
        ->pressAndWaitFor('Сбросить', 1)
        ->type('name', Str::random(256))
        ->type('address', Str::random(256))
        ->pressAndWaitFor('Изменить', 2)
        ->assertSee('Поле "Наименование" не должно превышать значение 255 символов.')
        ->assertSee('Поле "Адрес" не должно превышать значение 255 символов.')
        ->assertNoJavaScriptErrors();
});

it('redirects to the Show page after successfully submitting the form', function () {
    $installationObject = InstallationObject::factory()
        ->create(['name' => 'ТП-1']);

    $editUrl = route('installation-objects.edit', $installationObject);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->typeSlowly('name', 'ТП-2')
        ->pressAndWaitFor('Изменить', 2)
        ->assertUrlIs(route('installation-objects.show', $installationObject))
        ->assertSee('Данные успешно обновлены.')
        ->assertSee('ТП-2')
        ->assertNoJavaScriptErrors();
});

it('can reset the form', function () {
    $installationObject = InstallationObject::factory()->create();

    $editUrl = route('installation-objects.edit', $installationObject);
    $newName = Str::random();
    $newAddress = Str::random();

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->clear('name')
        ->type('name', $newName)
        ->clear('address')
        ->type('address', $newAddress)
        ->assertValue('input[name=name]', $newName)
        ->assertValue('input[name=address]', $newAddress)
        ->pressAndWaitFor('Сбросить', 2)
        ->assertValue('input[name=name]', $installationObject->name)
        ->assertValue('input[name=address]', $installationObject->address)
        ->assertNoJavaScriptErrors();
});

it('navigates from the Edit page to the Index page using the link', function () {
    $installationObject = InstallationObject::factory()->create();

    $editUrl = route('installation-objects.edit', $installationObject);

    $page = visit($editUrl)->on()->mobile();

    $page->assertUrlIs($editUrl)
        ->assertSeeLink('Список объектов установки')
        ->click('Список объектов установки')
        ->assertUrlIs(route('installation-objects.index'))
        ->assertNoJavaScriptErrors();
});

it('navigates back in the browser history after clicking on "Назад"', function () {
    $installationObject = InstallationObject::factory()->create();

    $showUrl = route('installation-objects.show', $installationObject);

    $page = visit($showUrl)->on()->mobile();

    $page->assertUrlIs($showUrl)
        ->assertSee($installationObject->name)
        ->click("a[href=\"/installation-objects/$installationObject->id/edit\"]")
        ->assertUrlIs(route('installation-objects.edit', $installationObject))
        ->assertSee('Редактировать объект установки')
        ->pressAndWaitFor('Назад', 2)
        ->assertUrlIs($showUrl)
        ->assertSee($installationObject->name)
        ->assertNoJavaScriptErrors();
});
