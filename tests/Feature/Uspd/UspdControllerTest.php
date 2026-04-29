<?php

use App\Http\Controllers\UspdController;
use App\Models\Uspd;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

describe('UspdController index action', function () {
    it('renders the uspds index page', function (Collection $uspds) {
        $uspdsCount = $uspds->count();
        $response = $this->get(action([UspdController::class, 'index']));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Uspd/Index')
                    ->has(
                        'uspds.data',
                        $uspdsCount,
                        fn (Assert $page) => $page
                            ->has('id')
                            ->has('model')
                            ->has('serial_number')
                            ->whereType('id', 'integer')
                            ->whereType('model', 'string')
                            ->whereType('serial_number', 'integer')
                    )
                    ->has('filter')
            );
    })->with([
        'three' => fn () => Uspd::factory()->count(3)->create(),
        'five' => fn () => Uspd::factory()->count(5)->create(),
    ]);

    it('filters uspds by model', function () {
        Uspd::factory()->create(['model' => 'RTR58A.LG-1-1']);
        Uspd::factory()->create(['model' => 'RTR8A.LRsGE-2-1-RUFG']);

        $response = $this->get(action([UspdController::class, 'index'], ['search' => 'RUFG']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('uspds.data', 1)
                ->where('uspds.data.0.model', 'RTR8A.LRsGE-2-1-RUFG')
                ->where('filter.search', 'RUFG')
        );
    });

    it('filters uspds by serial_number', function () {
        Uspd::factory()->create(['serial_number' => 4210987]);
        Uspd::factory()->create(['serial_number' => 4654231]);

        $response = $this->get(action([UspdController::class, 'index'], ['search' => 4210987]));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('uspds.data', 1)
                ->where('uspds.data.0.serial_number', 4210987)
                ->where('filter.search', '4210987')
        );
    });

    it('returns an empty collection when no results match', function () {
        Uspd::factory()->count(3)->create();

        $response = $this->get(action([UspdController::class, 'index'], ['search' => 'Этого нет']));

        $response->assertInertia(
            fn (Assert $page) => $page
                ->has('uspds.data', 0)
                ->where('filter.search', 'Этого нет')
        );
    });
});

describe('UspdController show action', function () {
    it('can view the uspd', function () {
        $uspd = Uspd::factory()->hasSimCards(2)->create();
        $response = $this->get(action([UspdController::class, 'show'], $uspd));

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Uspd/Show')
                ->has('uspd.id')
                ->has('uspd.model')
                ->has('uspd.serial_number')
                ->where('uspd.id', $uspd->id)
                ->where('uspd.model', $uspd->model)
                ->where('uspd.serial_number', $uspd->serial_number)
                ->whereType('uspd.id', 'integer')
                ->whereType('uspd.model', 'string')
                ->whereType('uspd.serial_number', 'integer')
                ->has('uspd.simCards', $uspd->simCards_count, fn (Assert $simCard) => $simCard
                    ->has('id')
                    ->has('number')
                    ->has('operator')
                    ->etc()
                    ->where('id', $uspd->simCards->first()->id)
                    ->where('number', $uspd->simCards->first()->number)
                    ->where('operator', $uspd->simCards->first()->operator)
                    ->whereType('id', 'integer')
                    ->whereType('number', 'string')
                    ->whereType('operator', 'string')
                )
                ->has('uspd.installationObject')
                ->whereType('uspd.installationObject', 'array')
                ->has('uspd.installationObject.id')
                ->has('uspd.installationObject.name')
                ->has('uspd.installationObject.address')
                ->whereType('uspd.installationObject.id', 'integer')
                ->whereType('uspd.installationObject.name', 'string')
                ->whereType('uspd.installationObject.address', 'string')
            );
    });
});
