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

describe('UspdController create action', function () {
    it('can view the Create page', function () {
        $response = $this->get(action([UspdController::class, 'create']));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Uspd/Create')
            );
    });
});

describe('UspdController store action', function () {
    it('can store a uspd', function () {
        $storeUrl = action([UspdController::class, 'store']);

        $response = $this->post($storeUrl, [
            'model' => 'RTR58A.LG-1-1',
            'serial_number' => 5993712,
            'lan_ip' => '192.168.0.101',
        ]);

        $uspd = Uspd::where('serial_number', 5993712)->first();

        $response->assertValid(['operator', 'number', 'ip'])
            ->assertRedirect(action([UspdController::class, 'show'], $uspd))
            ->assertInertiaFlash('message', 'УСПД успешно создан.');
    });

    it('requires valid data to store a uspd', function (string $field, mixed $value) {
        $validData = Uspd::factory()->raw();

        $storeUrl = action([UspdController::class, 'store']);
        $response = $this->post($storeUrl, [...$validData, $field => $value]);

        $response->assertRedirectBackWithErrors([$field]);
    })->with([
        'model is required' => ['model', ''],
        'model is not in [RTR8A.LRsGE-1-1-RUFG, RTR8A.LRsGE-2-1-RUFG, RTR8A.LGE-2-2-RUF, RTR58A.LG-1-1, RTR58A.LG-2-1]' => ['model', 'abc'],
        'serial_number is required' => ['serial_number', ''],
        'serial_number is not an integer' => ['serial_number', 'a993712'],
        'serial_number is not 7 digits' => ['serial_number', '59937121'],
        'lan_ip is not ipv4' => ['lan_ip', '192.168.2.300'],
    ]);

    it('requires a unique serial_number', function () {
        $uspd = Uspd::factory()->create();
        $storeUrl = action([UspdController::class, 'store']);

        $response = $this->post($storeUrl, [
            'model' => 'RTR58A.LG-1-1',
            'serial_number' => $uspd->serial_number,
        ]);

        $response->assertRedirectBackWithErrors(['serial_number']);
    });

    it('requires a unique lan_ip', function () {
        $uspd = Uspd::factory()->create(['lan_ip' => '192.168.3.102']);
        $storeUrl = action([UspdController::class, 'store']);

        $response = $this->post($storeUrl, [
            'model' => 'RTR58A.LG-1-1',
            'serial_number' => '1112233',
            'lan_ip' => $uspd->lan_ip,
        ]);

        $response->assertRedirectBackWithErrors(['lan_ip']);
    });

    it('accepts a default lan_ip', function () {
        $data = Uspd::factory()->make(['lan_ip' => '192.168.0.100'])->toArray();

        $response = $this->post(action([UspdController::class, 'store']), $data);

        $response->assertValid()
            ->assertRedirect();
    });
});

describe('UspdController destroy action', function () {
    it('deletes the uspd', function () {
        $uspd = Uspd::factory()->create();
        $response = $this->delete(action([UspdController::class, 'destroy'], $uspd));

        $this->assertDatabaseMissing('uspds', [
            'id' => $uspd->id,
        ]);

        $response->assertRedirect(action([UspdController::class, 'index']))
            ->assertInertiaFlash('message', 'УСПД успешно удалён.');
    });
});

describe('UspdController edit action', function () {
    it('can view the edit page with uspd data', function () {
        $uspd = Uspd::factory()->create();
        $response = $this->get(action([UspdController::class, 'edit'], $uspd));

        $response->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Uspd/Edit')
                    ->has('uspd')
                    ->where('uspd.id', $uspd->id)
                    ->where('uspd.model', $uspd->model)
                    ->where('uspd.serial_number', $uspd->serial_number)
                    ->where('uspd.lan_ip', $uspd->lan_ip)
                    ->whereType('uspd.id', 'integer')
                    ->whereType('uspd.model', 'string')
                    ->whereType('uspd.serial_number', 'integer')
                    ->whereType('uspd.lan_ip', 'string')
            );
    });
});

describe('UspdController update action', function () {
    beforeEach(function () {
        $this->uspd = Uspd::factory()->create([
            'model' => 'RTR58A.LG-1-1',
            'serial_number' => 5993712,
            'lan_ip' => '192.168.3.10',
        ]);
    });

    it('updates a uspd with valid data and redirects', function () {
        $updateUrl = action([UspdController::class, 'update'], $this->uspd);

        $response = $this->put($updateUrl, [
            'model' => 'RTR58A.LG-2-1',
            'serial_number' => '5993713',
            'lan_ip' => '192.168.3.11',
        ]);

        $response->assertValid(['model', 'serial_number', 'lan_ip'])
            ->assertRedirect(action([UspdController::class, 'show'], $this->uspd))
            ->assertInertiaFlash('message', 'УСПД успешно обновлён.');

        expect($this->uspd->fresh())
            ->model->toBe('RTR58A.LG-2-1')
            ->serial_number->toBe(5993713)
            ->lan_ip->toBe('192.168.3.11');
    });

    it('allows updating a uspd with its own serial_number and lan_ip', function () {
        $updateUrl = action([UspdController::class, 'update'], $this->uspd);

        $this->put($updateUrl, [
            'model' => 'RTR58A.LG-2-1',
            'serial_number' => $this->uspd->serial_number,
            'lan_ip' => $this->uspd->lan_ip,
        ]);

        expect($this->uspd->fresh())->model->toBe('RTR58A.LG-2-1');
    });

    it('requires valid data to update a uspd', function (string $field, mixed $value) {
        $updateUrl = action([UspdController::class, 'update'], $this->uspd);

        $validData = [
            'model' => 'RTR58A.LG-2-1',
            'serial_number' => '5993713',
            'lan_ip' => '192.168.1.25',
        ];

        $response = $this->put($updateUrl, [...$validData, $field => $value]);

        $response->assertRedirectBackWithErrors($field);
    })->with([
        'model is required' => ['model', ''],
        'model is not in [RTR8A.LRsGE-1-1-RUFG, RTR8A.LRsGE-2-1-RUFG, RTR8A.LGE-2-2-RUF, RTR58A.LG-1-1, RTR58A.LG-2-1]' => ['model', 'abc'],
        'serial_number is required' => ['serial_number', ''],
        'serial_number is not an integer' => ['serial_number', 'a993712'],
        'serial_number is not 7 digits' => ['serial_number', '59937121'],
        'lan_ip is not ipv4' => ['lan_ip', '192.168.2.300'],
    ]);

    it('fails validation when serial_number is already taken by another uspd', function () {
        $uspd2 = Uspd::factory()->create();
        $updateUrl = action([UspdController::class, 'update'], $this->uspd);

        $response = $this->put($updateUrl, [
            'model' => 'RTR8A.LGE-2-2-RUF',
            'serial_number' => $uspd2->number,
        ]);

        $response->assertRedirectBackWithErrors('serial_number');
    });

    it('fails validation when lan_ip is already taken by another uspd', function () {
        $uspd2 = Uspd::factory()->create(['lan_ip' => '192.168.0.1']);
        $updateUrl = action([UspdController::class, 'update'], $this->uspd);

        $response = $this->put($updateUrl, [
            'model' => 'RTR8A.LGE-2-2-RUF',
            'lan_ip' => $uspd2->lan_ip,
        ]);

        $response->assertRedirectBackWithErrors('lan_ip');
    });
});
