<?php

use App\Http\Controllers\UspdController;
use App\Http\Controllers\UspdSimCardController;
use App\Models\SimCard;
use App\Models\Uspd;
use Inertia\Testing\AssertableInertia as Assert;

describe('UspdSimCardController create action', function () {
    it('can view the Create page', function () {
        $uspd = Uspd::factory()->create();
        $simCards = SimCard::factory()->count(2)->create();

        $response = $this->get(action([UspdSimCardController::class, 'create'], $uspd));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Uspd/SimCard/Create')
                    ->has(
                        'uspd',
                        fn (Assert $prop) => $prop
                            ->has('id')
                            ->has('model')
                            ->has('serial_number')
                            ->where('model', $uspd->model)
                            ->where('serial_number', $uspd->serial_number)
                            ->whereType('id', 'integer')
                            ->whereType('model', 'string')
                            ->whereType('serial_number', 'integer')
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

    it('excludes sim cards that belong to a uspd and meters', function () {
        SimCard::factory()->forUspd()->create();
        SimCard::factory()->hasMeters()->create();
        $uspd = Uspd::factory()->create();

        $response = $this->get(action([UspdSimCardController::class, 'create'], $uspd));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 0)
        );
    });
});

describe('UspdSimCardController store action', function () {
    it('associates the sim card to the uspd', function () {
        $uspd = Uspd::factory()->create();
        $simCard = SimCard::factory()->create();

        $response = $this->post(
            action([UspdSimCardController::class, 'store'], $uspd),
            ['sim_card_id' => $simCard->id]
        );

        $response->assertRedirect(action([UspdController::class, 'show'], $uspd))
            ->assertInertiaFlash('message', 'Сим-карта успешно привязана к УСПД.');

        $associatedSimCard = $uspd->simCards()->first();

        expect($associatedSimCard->is($simCard))->toBeTrue();
    });

    it('requires valid data to associate the sim card to the uspd', function (string $field, mixed $value) {
        $uspd = Uspd::factory()->create();
        $action = action([UspdSimCardController::class, 'store'], $uspd);

        $this->post($action, [$field => $value])
            ->assertRedirectBackWithErrors([$field]);
    })->with([
        'sim_card_id is required' => ['sim_card_id', ''],
        'sim_card_id does not exist' => ['sim_card_id', 777],
        'sim_card_id is not an integer' => ['sim_card_id', '1'],
    ]);

    it('returns a validation error when the sim card is already attached with the meter', function () {
        $simCard = SimCard::factory()
            ->hasMeters()
            ->create();

        $uspd = Uspd::factory()->create();

        $response = $this->post(
            action([UspdSimCardController::class, 'store'], $uspd),
            ['sim_card_id' => $simCard->id]
        );

        $response->assertRedirectBackWithErrors();

        $associatedSimCard = $uspd->simCards()->first();

        expect($associatedSimCard)->toBe(null);
    });
});

describe('UspdSimCardController destroy action', function () {
    it('disassociates the sim card from the uspd', function () {
        $uspd = Uspd::factory()->create();
        $simCard = SimCard::factory()->create();

        $simCard->uspd()->associate($uspd);
        $simCard->save();

        $response = $this->from(action([UspdController::class, 'show'], $uspd))
            ->delete(action([UspdSimCardController::class, 'destroy'], [$uspd, $simCard]));

        $response->assertRedirect(action([UspdController::class, 'show'], $uspd))
            ->assertInertiaFlash('message', 'Сим-карта успешно отвязана от УСПД.');

        $associatedSimCard = $uspd->simCards()->first();

        expect($associatedSimCard)->toBe(null);
    });
});
