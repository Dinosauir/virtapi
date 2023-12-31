<?php

namespace Tests\Unit;


use App\Modules\Company\Data\CompanyStoreData;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Services\AbstractCompanyCreator;
use App\Modules\Station\Contracts\StationRepositoryInterface;
use App\Modules\Station\Contracts\StationSearcherInterface;
use App\Modules\Station\Data\StationSearchData;
use App\Modules\Station\Data\StationStoreData;
use App\Modules\Station\Data\StationUpdateData;
use App\Modules\Station\Models\Station;
use App\Modules\Station\Services\AbstractStationCreator;
use App\Modules\Station\Services\AbstractStationDestroyer;
use App\Modules\Station\Services\AbstractStationUpdater;
use App\Modules\Station\ValueObjects\LatitudeValueObject;
use App\Modules\Station\ValueObjects\LongitudeValueObject;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class StationTest extends TestCase
{
    private StationRepositoryInterface $stationRepository;

    private AbstractStationCreator $stationCreator;

    private AbstractStationUpdater $stationUpdater;

    private AbstractStationDestroyer $stationDestroyer;

    private AbstractCompanyCreator $companyCreator;

    private StationSearcherInterface $stationSearcher;

    public function setUp(): void
    {
        parent::setUp();
        $this->stationRepository = $this->app->make(StationRepositoryInterface::class);
        $this->stationDestroyer = $this->app->make(AbstractStationDestroyer::class);
        $this->stationCreator = $this->app->make(AbstractStationCreator::class);
        $this->stationUpdater = $this->app->make(AbstractStationUpdater::class);
        $this->companyCreator = $this->app->make(AbstractCompanyCreator::class);
        $this->stationSearcher = $this->app->make(StationSearcherInterface::class);
    }

    public function test_create_station(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $data = $this->createStoreData($company->id);

        $station = $this->stationCreator->create($data);

        $this->assertEquals($data->name, $station->name);
        $this->assertEquals($data->latitude->value(), $station->latitude);
        $this->assertEquals($data->longitude->value(), $station->longitude);
        $this->assertEquals(1, $station->id);
    }

    public function test_update_station(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $data = $this->createStoreData($company->id);

        $station = $this->stationCreator->create($data);

        $this->assertEquals($data->name, $station->name);
        $this->assertEquals($data->latitude->value(), $station->latitude);
        $this->assertEquals($data->longitude->value(), $station->longitude);
        $this->assertEquals(1, $station->id);

        $data = $this->createUpdateData($station);

        $station = $this->stationUpdater->update($data);

        $this->assertEquals($data->name, $station->name);
        $this->assertEquals($data->latitude->value(), $station->latitude);
        $this->assertEquals($data->longitude->value(), $station->longitude);
        $this->assertEquals($data->address, $station->address);
        $this->assertEquals($data->company_id, $station->company_id);
    }

    public function test_remove_station(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $data = new StationStoreData(
            name: $this->faker->name,
            latitude: LatitudeValueObject::make($this->faker->latitude),
            longitude: LongitudeValueObject::make($this->faker->longitude),
            company_id: $company->id,
            address: $this->faker->address
        );

        $station = $this->stationCreator->create($data);

        $this->assertEquals($data->name, $station->name);
        $this->assertEquals($data->latitude->value(), $station->latitude);
        $this->assertEquals($data->longitude->value(), $station->longitude);
        $this->assertEquals(1, $station->id);

        $response = $this->stationDestroyer->destroy($station->id);

        $this->assertTrue($response);
    }

    public function test_show_station(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $data = new StationStoreData(
            name: $this->faker->name,
            latitude: LatitudeValueObject::make($this->faker->latitude),
            longitude: LongitudeValueObject::make($this->faker->longitude),
            company_id: $company->id,
            address: $this->faker->address
        );

        $station = $this->stationCreator->create($data);

        Cache::tags(Station::getCollectionCacheKey())->flush();
        $station = $this->stationRepository->getStation($station->id);

        $this->assertEquals($data->name, $station->name);
        $this->assertEquals($data->latitude->value(), $station->latitude);
        $this->assertEquals($data->longitude->value(), $station->longitude);
        $this->assertEquals(1, $station->id);
    }

    public function test_show_stations(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $data = new StationStoreData(
            name: $this->faker->name,
            latitude: LatitudeValueObject::make($this->faker->latitude),
            longitude: LongitudeValueObject::make($this->faker->longitude),
            company_id: $company->id,
            address: $this->faker->address
        );

        $this->stationCreator->create($data);

        $paginator = $this->stationRepository->getAllStations(1);

        $this->assertCount(1, $paginator);
    }

    public function test_station_search(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $latitude = LatitudeValueObject::make($this->faker->latitude);
        $longitude = LongitudeValueObject::make($this->faker->longitude);
        $data = new StationStoreData(
            name: $this->faker->name,
            latitude: $latitude,
            longitude: $longitude,
            company_id: $company->id,
            address: $this->faker->address
        );

        $this->stationCreator->create($data);

        $result = $this->stationSearcher->getStationsInRadius(
            new StationSearchData(
                page: 1,
                latitude: $latitude,
                longitude: $longitude,
                company_id: 1,
                radius: 50
            )
        );

        $this->assertCount(1, $result);
    }

    public function test_station_search_out_of_range(): void
    {
        $company = $this->createCompany();
        $this->assertEquals(1, $company->id);

        $data = new StationStoreData(
            name: $this->faker->name,
            latitude: LatitudeValueObject::make(21),
            longitude: LongitudeValueObject::make(21),
            company_id: $company->id,
            address: $this->faker->address
        );

        $this->stationCreator->create($data);

        $data = new StationSearchData(
            page: 1,
            latitude: LatitudeValueObject::make(89),
            longitude: LongitudeValueObject::make(90),
            company_id: 1,
            radius: 50
        );

        $this->assertEquals([
            'page' => 1,
            'latitude' => LatitudeValueObject::make(89)->value(),
            'longitude' => LongitudeValueObject::make(90)->value(),
            'company_id' => 1,
            'radius' => 50
        ], $data->toArray());

        $result = $this->stationSearcher->getStationsInRadius($data);

        $this->assertCount(0, $result);
    }

    private function createCompany(): Company
    {
        return $this->companyCreator->create(
            new CompanyStoreData(
                name: $this->faker->company,
                parent_company_id: null
            )
        );
    }

    private function createStoreData(int $company_id): StationStoreData
    {
        $name = $this->faker->name;
        $latitude = $this->faker->latitude;
        $longitude = $this->faker->longitude;
        $address = $this->faker->address;

        $data = new StationStoreData(
            name: $name,
            latitude: LatitudeValueObject::make($latitude),
            longitude: LongitudeValueObject::make($longitude),
            company_id: $company_id,
            address: $address
        );

        $this->assertEquals($address, $data->address);
        $this->assertEquals($latitude, $data->latitude->value());
        $this->assertEquals($longitude, $data->longitude->value());
        $this->assertEquals($name, $data->name);
        $this->assertEquals($company_id, $data->company_id);
        $this->assertEquals(compact('name', 'latitude', 'longitude', 'address', 'company_id'), $data->toArray());

        return $data;
    }

    private function createUpdateData(Station $station): StationUpdateData
    {
        $name = $this->faker->name;
        $latitude = $this->faker->latitude;
        $longitude = $this->faker->longitude;
        $address = $this->faker->address;
        $company_id = $station->company_id;
        $id = $station->id;
        $data = new StationUpdateData(
            id: $id,
            name: $name,
            latitude: LatitudeValueObject::make($latitude),
            longitude: LongitudeValueObject::make($longitude),
            company_id: $company_id,
            address: $address
        );

        $this->assertEquals($address, $data->address);
        $this->assertEquals($latitude, $data->latitude->value());
        $this->assertEquals($longitude, $data->longitude->value());
        $this->assertEquals($name, $data->name);
        $this->assertEquals($station->company_id, $data->company_id);

        $this->assertEquals(
            compact('name', 'latitude', 'longitude', 'address', 'company_id', 'id'),
            $data->toArray()
        );

        return $data;
    }
}
