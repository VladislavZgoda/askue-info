<?php

use App\Http\Controllers\MeterController;
use App\Http\Controllers\MeterSimCardController;
use App\Models\InstallationObject;
use App\Models\Meter;
use App\Models\SimCard;
use Inertia\Testing\AssertableInertia as Assert;

describe('MeterSimCardController create action', function () {
    it('can view the Create page', function () {
        $meter = Meter::factory()->create();
        $simCards = SimCard::factory()->count(2)->create();

        $response = $this->get(action([MeterSimCardController::class, 'create'], $meter));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Meter/SimCard/Create')
                    ->has(
                        'meter',
                        fn (Assert $prop) => $prop
                            ->has('id')
                            ->has('model')
                            ->has('serial_number')
                            ->where('id', $meter->id)
                            ->where('model', $meter->model)
                            ->where('serial_number', $meter->serial_number)
                            ->whereType('id', 'integer')
                            ->whereType('model', 'string')
                            ->whereType('serial_number', 'string')
                    )
                    ->has('simCards', $simCards->count(), fn (Assert $simCard) => $simCard
                        ->has('id')
                        ->has('operator')
                        ->has('number')
                        ->where('id', $simCards->first()->id)
                        ->where('operator', $simCards->first()->operator)
                        ->where('number', $simCards->first()->number)
                        ->whereType('id', 'integer')
                        ->whereType('operator', 'string')
                        ->whereType('number', 'string')
                    )
                    ->whereType('simCards', 'array')
            );
    });

    it('excludes sim cards that belong to a uspd', function () {
        SimCard::factory()->forUspd()->create();
        $meter = Meter::factory()->create();

        $response = $this->get(action([MeterSimCardController::class, 'create'], $meter));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 0)
        );
    });

    it('includes sim cards already linked to a meter sharing the same installation object', function () {
        $installationObject = InstallationObject::factory()->create();
        $meter1 = Meter::factory()->for($installationObject)->create();
        $meter2 = Meter::factory()->for($installationObject)->create();
        $simCard = SimCard::factory()->create();

        $meter2->simCards()->attach($simCard);

        $response = $this->get(action([MeterSimCardController::class, 'create'], $meter1));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 1,
                    fn (Assert $card) => $card
                        ->has('id')
                        ->where('id', $simCard->id)
                        ->etc()
                )
        );
    });

    it('excludes sim cards linked to a meter in a different installation object', function () {
        $meter1 = Meter::factory()->create();
        $meter2 = Meter::factory()->create();
        $simCard = SimCard::factory()->create();

        $meter2->simCards()->attach($simCard);

        $response = $this->get(action([MeterSimCardController::class, 'create'], $meter1));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 0)
        );
    });

    it('excludes sim cards already attached to the current meter', function () {
        $meter = Meter::factory()->create();
        $simCard = SimCard::factory()->create();

        $meter->simCards()->attach($simCard);

        $response = $this->get(action([MeterSimCardController::class, 'create'], $meter));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 0)
        );
    });
});

describe('MeterSimCardController store action', function () {
    it('attaches the sim card to the meter', function () {
        $meter = Meter::factory()->create();
        $simCard = SimCard::factory()->create();

        $response = $this->post(
            action([MeterSimCardController::class, 'store'], $meter),
            ['sim_card_id' => $simCard->id]
        );

        $response->assertRedirect(action([MeterController::class, 'show'], $meter))
            ->assertInertiaFlash('message', 'Сим-карта успешно привязана к прибору учёта.');

        $attachedSimCard = SimCard::whereAttachedTo($meter)->first();

        expect($attachedSimCard->is($simCard))->toBeTrue();
    });

    it('requires valid data to attach the sim card to the meter', function (string $field, mixed $value) {
        $meter = Meter::factory()->create();
        $action = action([MeterSimCardController::class, 'store'], $meter);

        $this->post($action, [$field => $value])
            ->assertRedirectBackWithErrors([$field]);
    })->with([
        'sim_card_id is required' => ['sim_card_id', ''],
        'sim_card_id does not exist' => ['sim_card_id', 777],
        'sim_card_id is not an integer' => ['sim_card_id', '1'],
    ]);

    it('returns a validation error when the pivot model throws and it also does not attach the sim card', function () {
        $simCard = SimCard::factory()->create();

        $meter = Meter::factory()
            ->withoutInstallationObject()
            ->create();

        $response = $this->post(
            action([MeterSimCardController::class, 'store'], $meter),
            ['sim_card_id' => $simCard->id]
        );

        $response->assertRedirectBackWithErrors();

        $attachedSimCard = SimCard::whereAttachedTo($meter)->first();

        expect($attachedSimCard)->toBe(null);
    });
});

describe('MeterSimCardController destroy action', function () {
    it('detaches the sim card from the meter', function () {
        $meter = Meter::factory()->create();
        $simCard = SimCard::factory()->create();

        $meter->simCards()->attach($simCard);

        $response = $this->from(action([MeterController::class, 'show'], $meter))
            ->delete(action([MeterSimCardController::class, 'destroy'], [$meter, $simCard]));

        $response->assertRedirect(action([MeterController::class, 'show'], $meter))
            ->assertInertiaFlash('message', 'Сим-карта успешно отвязана от прибора учёта.');

        $attachedSimCard = SimCard::whereAttachedTo($meter)->first();

        expect($attachedSimCard)->toBe(null);
    });
});
