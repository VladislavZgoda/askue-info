<?php

use App\Http\Controllers\MeterController;
use App\Models\Meter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

describe('MeterController index action', function () {
    it('renders the meters index page', function (Collection $meters) {
        $meterCount = $meters->count();
        $response = $this->get(action([MeterController::class, 'index']));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Meter/Index')
                    ->has(
                        'meters',
                        $meterCount,
                        fn (Assert $page) => $page
                            ->has('id')
                            ->has('model')
                            ->has('serial_number')
                            ->whereType('id', 'integer')
                            ->whereType('model', 'string')
                            ->whereType('serial_number', 'string')
                    )
                    ->has('filter')
            );
    })->with([
        'three' => fn () => Meter::factory()->count(3)->create(),
        'five' => fn () => Meter::factory()->count(5)->create(),
    ]);

    it('filters meters by model name', function () {
        Meter::factory()->create(['model' => 'Меркурий 236']);
        Meter::factory()->create(['model' => 'СЭТ-4ТМ.03М']);

        $response = $this->get(action([MeterController::class, 'index'], ['search' => '236']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('meters', 1)
                ->where('meters.0.model', 'Меркурий 236')
                ->where('filter.search', '236')
        );
    });

    it('filters meters by serial number', function () {
        Meter::factory()->create(['serial_number' => '123456789']);
        Meter::factory()->create(['serial_number' => '987654321']);

        $response = $this->get(action([MeterController::class, 'index'], ['search' => '123456789']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('meters', 1)
                ->where('meters.0.serial_number', '123456789')
                ->where('filter.search', '123456789')
        );
    });

    it('returns an empty collection when no results match', function () {
        Meter::factory()->count(3)->create();

        $response = $this->get(action([MeterController::class, 'index'], ['search' => 'Этого нет']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('meters', 0)
                ->where('filter.search', 'Этого нет')
        );
    });
});

describe('MeterController create action', function () {
    it('can view the Create page', function () {
        $response = $this->get(action([MeterController::class, 'create']));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Meter/Create')
            );
    });
});

describe('MeterController store action', function () {
    it('can store a meter', function () {
        $storeUrl = action([MeterController::class, 'store']);

        $response = $this->post($storeUrl, [
            'model' => 'Меркурий 236 тест',
            'serial_number' => '098765123',
        ]);

        $meter = Meter::where('model', 'Меркурий 236 тест')->first();

        $response->assertValid(['model', 'serial_number'])
            ->assertRedirect(action([MeterController::class, 'show'], $meter))
            ->assertInertiaFlash('message', 'Прибор учёта успешно создан.');
    });

    it('requires valid data to store a meter', function (string $field, mixed $value) {
        $validData = [
            'model' => 'Меркурий 236 тест',
            'serial_number' => '098765123',
        ];

        $storeUrl = action([MeterController::class, 'store']);
        $response = $this->post($storeUrl, [...$validData, $field => $value]);

        $response->assertRedirectBackWithErrors([$field]);
    })->with([
        'model is required' => ['model', ''],
        'model is too long' => ['model', Str::random(256)],
        'serial_number is required' => ['serial_number', ''],
        'serial_number is too long' => ['serial_number', Str::random(256)],
        'serial_number must contain only digits' => ['serial_number', '123des'],
    ]);

    it('requires a unique serial number', function () {
        $meter = Meter::factory()->create();
        $storeUrl = action([MeterController::class, 'store']);

        $response = $this->post($storeUrl, [
            'serial_number' => $meter->serial_number,
        ]);

        $response->assertRedirectBackWithErrors(['serial_number']);
    });
});

describe('MeterController show action', function () {
    it('can view the meter', function () {
        $meter = Meter::factory()->hasSimCards(2)->create();
        $meter->load('simCards');

        $response = $this->get(action([MeterController::class, 'show'], $meter));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Meter/Show')
                ->has('id')
                ->has('model')
                ->has('serial_number')
                ->where('id', $meter->id)
                ->where('model', $meter->model)
                ->where('serial_number', $meter->serial_number)
                ->whereType('id', 'integer')
                ->whereType('model', 'string')
                ->whereType('serial_number', 'string')
                ->has('simCards', $meter->simCards_count, fn (Assert $simCard) => $simCard
                    ->has('id')
                    ->has('number')
                    ->has('operator')
                    ->etc()
                    ->where('id', $meter->simCards->first()->id)
                    ->where('number', $meter->simCards->first()->number)
                    ->where('operator', $meter->simCards->first()->operator)
                    ->whereType('id', 'integer')
                    ->whereType('number', 'string')
                    ->whereType('operator', 'string')
                )
            );
    });
});
