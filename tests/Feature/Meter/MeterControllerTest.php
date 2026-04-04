<?php

use App\Http\Controllers\MeterController;
use App\Models\Meter;
use Illuminate\Database\Eloquent\Collection;
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
