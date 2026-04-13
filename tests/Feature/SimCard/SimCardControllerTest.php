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
