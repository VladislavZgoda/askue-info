<?php

use App\Http\Controllers\SimCardController;
use App\Models\SimCard;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

describe('SimCardController index action', function () {
    it('renders the sim cards index page', function (Collection $simCards) {
        $simCardsCount = $simCards->count();
        $response = $this->get(action([SimCardController::class, 'index']));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('SimCard/Index')
                    ->has(
                        'simCards',
                        $simCardsCount,
                        fn (Assert $page) => $page
                            ->has('id')
                            ->has('number')
                            ->has('operator')
                            ->whereType('id', 'integer')
                            ->whereType('number', 'string')
                            ->whereType('operator', 'string')
                    )
                    ->has('filter')
            );
    })->with([
        'three' => fn () => SimCard::factory()->count(3)->create(),
        'five' => fn () => SimCard::factory()->count(5)->create(),
    ]);

    it('filters sim cards by number', function () {
        SimCard::factory()->create(['number' => '89181111111']);
        SimCard::factory()->create(['number' => '89182222222']);

        $response = $this->get(action([SimCardController::class, 'index'], ['search' => '89181']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 1)
                ->where('simCards.0.number', '89181111111')
                ->where('filter.search', '89181')
        );
    });

    it('filters sim cards by operator', function () {
        SimCard::factory()->create(['operator' => 'МТС']);
        SimCard::factory()->create(['operator' => 'МегаФон']);

        $response = $this->get(action([SimCardController::class, 'index'], ['search' => 'МТС']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 1)
                ->where('simCards.0.operator', 'МТС')
                ->where('filter.search', 'МТС')
        );
    });

    it('returns an empty collection when no results match', function () {
        SimCard::factory()->count(3)->create();

        $response = $this->get(action([SimCardController::class, 'index'], ['search' => 'Этого нет']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('simCards', 0)
                ->where('filter.search', 'Этого нет')
        );
    });
});

describe('SimCardController create action', function () {
    it('can view the Create page', function () {
        $response = $this->get(action([SimCardController::class, 'create']));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('SimCard/Create')
            );
    });
});

describe('SimCardController store action', function () {
    it('can store a simCard', function () {
        $storeUrl = action([SimCardController::class, 'store']);

        $response = $this->post($storeUrl, [
            'operator' => 'МТС',
            'number' => '89181111111',
            'ip' => '192.168.8.1',
        ]);

        $simCard = SimCard::where('number', '89181111111')->first();

        $response->assertValid(['operator', 'number', 'ip'])
            ->assertRedirect(action([SimCardController::class, 'show'], $simCard))
            ->assertInertiaFlash('message', 'Сим-карта успешно создана.');
    });

    it('requires valid data to store a sim card', function (string $field, mixed $value) {
        $validData = SimCard::factory()->raw();

        $storeUrl = action([SimCardController::class, 'store']);
        $response = $this->post($storeUrl, [...$validData, $field => $value]);

        $response->assertRedirectBackWithErrors([$field]);
    })->with([
        'operator is required' => ['operator', ''],
        'operator is not in [МТС, Билайн, МегаФон]' => ['operator', 'abc'],
        'number is required' => ['number', ''],
        'number is too long' => ['number', '8918111111121'],
        'number the number must match regex:/^(\+7|8)\d+$/' => ['number', '618111111s'],
        'ip is not ipv4' => ['ip', '192.168.2.300'],
    ]);

    it('requires a unique number', function () {
        $simCard = SimCard::factory()->create();
        $storeUrl = action([SimCardController::class, 'store']);

        $response = $this->post($storeUrl, [
            'operator' => 'МТС',
            'number' => $simCard->number,
        ]);

        $response->assertRedirectBackWithErrors(['number']);
    });

    it('requires a unique ip', function () {
        $simCard = SimCard::factory()->create(['ip' => '192.168.0.100']);
        $storeUrl = action([SimCardController::class, 'store']);

        $response = $this->post($storeUrl, [
            'operator' => 'МТС',
            'number' => '89181111111',
            'ip' => $simCard->ip,
        ]);

        $response->assertRedirectBackWithErrors(['ip']);
    });

    it('accepts null ip', function () {
        $data = SimCard::factory()->make(['ip' => null])->toArray();

        $response = $this->post(route('sim-cards.store'), $data);

        $response->assertValid()
            ->assertRedirect();
    });
});

describe('SimCardController show action', function () {
    it('can view the sim card and the meters to which it belongs', function () {
        $simCard = SimCard::factory()->hasMeters(1)->create();

        $response = $this->get(action([SimCardController::class, 'show'], $simCard));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('SimCard/Show')
                    ->has('id')
                    ->has('number')
                    ->has('operator')
                    ->etc()
                    ->where('id', $simCard->id)
                    ->where('number', $simCard->number)
                    ->where('operator', $simCard->operator)
                    ->whereType('id', 'integer')
                    ->whereType('number', 'string')
                    ->whereType('operator', 'string')
                    ->has(
                        'meters',
                        $simCard->meters_count,
                        fn (Assert $meter) => $meter
                            ->has('id')
                            ->has('model')
                            ->has('serial_number')
                            ->where('id', $simCard->meters->first()->id)
                            ->where('model', $simCard->meters->first()->model)
                            ->where('serial_number', $simCard->meters->first()->serial_number)
                            ->whereType('id', 'integer')
                            ->whereType('model', 'string')
                            ->whereType('serial_number', 'string')
                    )
            );
    });

    it('can view the sim card and the uspd to which it belongs', function () {
        $simCard = SimCard::factory()->forUspd()->create();

        $response = $this->get(action([SimCardController::class, 'show'], $simCard));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('SimCard/Show')
                    ->has('id')
                    ->has('number')
                    ->has('operator')
                    ->etc()
                    ->where('id', $simCard->id)
                    ->where('number', $simCard->number)
                    ->where('operator', $simCard->operator)
                    ->whereType('id', 'integer')
                    ->whereType('number', 'string')
                    ->whereType('operator', 'string')
                    ->has('uspd')
                    ->whereType('uspd', 'array')
                    ->has('uspd.id')
                    ->has('uspd.model')
                    ->has('uspd.serial_number')
                    ->whereType('uspd.id', 'integer')
                    ->whereType('uspd.model', 'string')
                    ->whereType('uspd.serial_number', 'integer')
            );
    });
});

describe('SimCardController destroy action', function () {
    it('deletes the sim card', function () {
        $simCard = SimCard::factory()->create();
        $response = $this->delete(action([SimCardController::class, 'destroy'], $simCard));

        $this->assertDatabaseMissing('meters', [
            'id' => $simCard->id,
        ]);

        $response->assertRedirect(action([SimCardController::class, 'index']))
            ->assertInertiaFlash('message', 'Сим-карта успешно удалена.');
    });
});

describe('SimCardController edit action', function () {
    it('can view the edit page with sim card data', function () {
        $simCard = SimCard::factory()->create(['ip' => '192.168.1.1']);
        $response = $this->get(action([SimCardController::class, 'edit'], $simCard));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('SimCard/Edit')
                    ->has('simCard')
                    ->where('simCard.id', $simCard->id)
                    ->where('simCard.operator', $simCard->operator)
                    ->where('simCard.number', $simCard->number)
                    ->where('simCard.ip', $simCard->ip)
                    ->whereType('simCard.id', 'integer')
                    ->whereType('simCard.operator', 'string')
                    ->whereType('simCard.number', 'string')
                    ->whereType('simCard.ip', 'string')
            );
    });
});

describe('SimCardController update action', function () {
    beforeEach(function () {
        $this->simCard = SimCard::factory()->create([
            'operator' => 'Билайн',
            'ip' => '192.168.1.1',
        ]);
    });

    it('updates a sim card with valid data and redirects', function () {
        $updateUrl = action([SimCardController::class, 'update'], $this->simCard);

        $response = $this->put($updateUrl, [
            'operator' => 'МТС',
            'number' => '89181112233',
            'ip' => '192.168.1.10',
        ]);

        $response->assertValid(['operator', 'number', 'ip'])
            ->assertRedirect(action([SimCardController::class, 'show'], $this->simCard))
            ->assertInertiaFlash('message', 'Сим-карта успешно обновлена.');

        expect($this->simCard->fresh())
            ->operator->toBe('МТС')
            ->number->toBe('89181112233')
            ->ip->toBe('192.168.1.10');
    });

    it('allows updating a sim card with its own operator and number', function () {
        $updateUrl = action([SimCardController::class, 'update'], $this->simCard);

        $this->put($updateUrl, [
            'operator' => 'МТС',
            'number' => $this->simCard->number,
            'ip' => $this->simCard->ip,
        ]);

        expect($this->simCard->fresh())->operator->toBe('МТС');
    });

    it('requires valid data to update a sim card', function (string $field, mixed $value) {
        $updateUrl = action([SimCardController::class, 'update'], $this->simCard);

        $validData = [
            'operator' => 'МТС',
            'number' => '89181112233',
            'ip' => '192.168.1.2',
        ];

        $response = $this->put($updateUrl, [...$validData, $field => $value]);

        $response->assertRedirectBackWithErrors($field);
    })->with([
        'operator is required' => ['operator', ''],
        'operator is not in [МТС, Билайн, МегаФон]' => ['operator', 'abc'],
        'number is required' => ['number', ''],
        'number is too long' => ['number', '8918111222333'],
        'number format is incorrect' => ['number', '39181112233'],
        'ip format is incorrect' => ['ip', '192.168.1.300'],
    ]);

    it('fails validation when number is already taken by another sim card', function () {
        $simCard2 = SimCard::factory()->create();
        $updateUrl = action([SimCardController::class, 'update'], $this->simCard);

        $response = $this->put($updateUrl, [
            'operator' => 'МТС',
            'number' => $simCard2->number,
        ]);

        $response->assertRedirectBackWithErrors('number');
    });

    it('fails validation when ip is already taken by another sim card', function () {
        $simCard2 = SimCard::factory()->create(['ip' => '192.168.0.1']);
        $updateUrl = action([SimCardController::class, 'update'], $this->simCard);

        $response = $this->put($updateUrl, [
            'operator' => 'МТС',
            'ip' => $simCard2->ip,
        ]);

        $response->assertRedirectBackWithErrors('ip');
    });
});
