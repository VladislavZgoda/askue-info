<?php

use App\Models\InstallationObject;
use App\Models\Meter;
use App\Models\SimCard;
use App\Models\Uspd;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('checks that sim card with meter cannot be assigned to uspd', function ()
{
    $meter = Meter::factory()->create();
    $simCard = SimCard::factory()->create();

    $meter->simCards()->attach($simCard);

    $uspd = Uspd::factory()
        ->withoutInstallationObject()
        ->create();

    expect(fn() => $simCard->uspd()->associate($uspd)->save())
        ->toThrow(Exception::class, 'SimCard не может одновременно принадлежать Meter и Uspd');

    //Проверяем, что uspd_id не изменился.
    expect($simCard->fresh()->uspd_id)->toBeNull();
});

it('checks that sim card with uspd cannot be attached to meter', function ()
{
    $uspd = Uspd::factory()
        ->withoutInstallationObject()
        ->create();

    $meter = Meter::factory()->create();
    $simCard = SimCard::factory()->for($uspd)->create();

    expect(fn() => $meter->simCards()->attach($simCard))
        ->toThrow(Exception::class, 'Нельзя привязать SimCard к Meter, так как она уже принадлежит Uspd');

    // Проверяем, что запись в pivot таблице не создалась.
    expect($simCard->meters()->exists())->toBeFalse();
});

it('checks that all meters of a sim card must belong to the same installation object', function ()
{
    $object1 = InstallationObject::factory()->create();
    $object2 = InstallationObject::factory()->create();

    $meterOnObject1 = Meter::factory()->for($object1)->create();
    $meterOnObject2 = Meter::factory()->for($object2)->create();

    $simCard = SimCard::factory()->create();

    $meterOnObject1->simCards()->attach($simCard);

    expect(fn() => $meterOnObject2->simCards()->attach($simCard))
        ->toThrow(Exception::class, 'SimCard может быть привязана только к Meter, принадлежащим одному InstallationObject');

    // Проверяем, что второй счётчик не привязался.
    expect($simCard->meters->pluck('id'))->not->toContain($meterOnObject2->id);
});

it('checks that meter without installation object cannot be attached to sim card', function ()
{
    $meter = Meter::factory()
        ->withoutInstallationObject()
        ->create();

    $simCard = SimCard::factory()->create();

    expect(fn() => $meter->simCards()->attach($simCard))
        ->toThrow(Exception::class, 'Все Meter должны иметь InstallationObject');
});

it('checks that sim card can be attached to meters from the same installation object', function ()
{
    $installationObject = InstallationObject::factory()->create();

    $meters = Meter::factory()
        ->count(2)
        ->for($installationObject)
        ->create();

    $simCard = SimCard::factory()->create();
    $simCard->meters()->attach($meters->pluck('id'));

    expect($simCard->meters()->count())->toBe(2);
});
