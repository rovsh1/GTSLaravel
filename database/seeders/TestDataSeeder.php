<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\System\FillCalculatedPriceCalendar;
use Sdk\Shared\Enum\Contract\StatusEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

class TestDataSeeder extends Seeder
{
    private const TEST_SUPPLIER_ID = 299;

    public function run(): void
    {
        if (app()->environment('prod', 'production') || DB::table('hotel_price_groups')->exists()) {
            return;
        }

        DB::unprepared(file_get_contents(__DIR__ . '/sql/test_supplier.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/sql/test_clients.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/sql/test_hotels.sql'));
        app(FillCalculatedPriceCalendar::class)->execute(75);

//        DB::table('hotel_calculated_price_calendar')->delete();
//        DB::table('hotel_season_price_calendar')->delete();
//        DB::table('hotel_season_prices')->delete();
//        DB::table('hotel_price_groups')->delete();
//
//        DB::table('hotel_price_groups')
//            ->insert([
//                ['id' => 1, 'rate_id' => 251, 'guests_count' => 1, 'is_resident' => 1],
//                ['id' => 2, 'rate_id' => 251, 'guests_count' => 1, 'is_resident' => 0],
//                ['id' => 3, 'rate_id' => 251, 'guests_count' => 2, 'is_resident' => 1],
//                ['id' => 4, 'rate_id' => 251, 'guests_count' => 2, 'is_resident' => 0],
//                ['id' => 5, 'rate_id' => 251, 'guests_count' => 3, 'is_resident' => 1],
//                ['id' => 6, 'rate_id' => 251, 'guests_count' => 3, 'is_resident' => 0],
//            ]);
//
//        DB::table('hotel_season_prices')
//            ->insert([
//                ['season_id' => 1197, 'group_id' => 1, 'room_id' => 233, 'price' => 500000],
//                ['season_id' => 1197, 'group_id' => 2, 'room_id' => 233, 'price' => 600000],
//                ['season_id' => 1197, 'group_id' => 3, 'room_id' => 233, 'price' => 650000],
//                ['season_id' => 1197, 'group_id' => 4, 'room_id' => 233, 'price' => 700000],
//
//                ['season_id' => 1197, 'group_id' => 1, 'room_id' => 238, 'price' => 1000000],
//                ['season_id' => 1197, 'group_id' => 2, 'room_id' => 238, 'price' => 2000000],
//                ['season_id' => 1197, 'group_id' => 3, 'room_id' => 238, 'price' => 1000000],
//                ['season_id' => 1197, 'group_id' => 4, 'room_id' => 238, 'price' => 2000000],
//                ['season_id' => 1197, 'group_id' => 5, 'room_id' => 238, 'price' => 1000000],
//                ['season_id' => 1197, 'group_id' => 6, 'room_id' => 238, 'price' => 2000000],
//            ]);

//        app(FillCalculatedPriceCalendar::class)->execute(61);
//
        $testSupplierId = self::TEST_SUPPLIER_ID;
        $supplierSeasonId = 1;

//        $supplierSeasonId = DB::table('supplier_seasons')->insertGetId([
//            'supplier_id' => $testSupplierId,
//            'number' => '2023',
//            'date_start' => '2023-01-01',
//            'date_end' => '2023-12-31',
//            'created_at' => now(),
//            'updated_at' => now()
//        ]);
//
//        DB::table('supplier_cities')->insert([
//            [
//                'supplier_id' => $testSupplierId,
//                'city_id' => 1,
//            ],
//            [
//                'supplier_id' => $testSupplierId,
//                'city_id' => 4,
//            ],
//        ]);
//
//        DB::table('supplier_requisites')->insert([
//            ['supplier_id' => $testSupplierId, 'inn' => '12345678', 'director_full_name' => 'John Doe']
//        ]);
//
//        $this->seedTransferSupplierData($testSupplierId, $supplierSeasonId);
//        $this->seedAirportSupplierData($testSupplierId, $supplierSeasonId);
    }

    private function seedTransferSupplierData(int $supplierId, int $seasonId): void
    {
        $carId = DB::table('supplier_cars')->insertGetId([
            'supplier_id' => $supplierId,
            'car_id' => 1,
        ]);

        $service1Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::TRANSFER_FROM_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $service2Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::TRANSFER_TO_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $service3Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::CAR_RENT_WITH_DRIVER,
            'data' => json_encode(['cityId' => 1]),
        ]);

        $railwayStationId = DB::table('r_railway_stations')->insertGetId([
            'city_id' => 1,
        ]);

        DB::table('r_railway_stations_translation')->insert([
            'translatable_id' => $railwayStationId,
            'language' => 'ru',
            'name' => 'Ж/Д Вокзал г. Ташкент',
        ]);

        $service4Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            'data' => json_encode(['railwayStationId' => $railwayStationId, 'cityId' => 1]),
        ]);

        $service5Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            'data' => json_encode(['railwayStationId' => $railwayStationId, 'cityId' => 1]),
        ]);

        $service6Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::INTERCITY_TRANSFER,
            'data' => json_encode(['fromCityId' => 1, 'toCityId' => 4, 'returnTripIncluded' => true]),
        ]);

        $service7Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::DAY_CAR_TRIP,
            'data' => json_encode(['cityId' => 1]),
        ]);

        $contract1Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::TRANSFER_FROM_AIRPORT,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contract2Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::TRANSFER_TO_AIRPORT,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contract3Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::CAR_RENT_WITH_DRIVER,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contract4Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contract5Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contract6Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::INTERCITY_TRANSFER,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $contract7Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::DAY_CAR_TRIP,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('supplier_service_contracts')->insert([
            ['contract_id' => $contract1Id, 'service_id' => $service1Id],
            ['contract_id' => $contract2Id, 'service_id' => $service2Id],
            ['contract_id' => $contract3Id, 'service_id' => $service3Id],
            ['contract_id' => $contract4Id, 'service_id' => $service4Id],
            ['contract_id' => $contract5Id, 'service_id' => $service5Id],
            ['contract_id' => $contract6Id, 'service_id' => $service6Id],
            ['contract_id' => $contract7Id, 'service_id' => $service7Id],
        ]);

        DB::table('supplier_car_prices')->insert([
            [
                'service_id' => $service1Id,
                'season_id' => $seasonId,
                'car_id' => $carId,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 20000,
                'prices_gross' => '[ { "amount": 30000, "currency": "UZS" }, { "amount": 25, "currency": "USD" } ]'
            ],
            [
                'service_id' => $service2Id,
                'season_id' => $seasonId,
                'car_id' => $carId,

                'currency' => CurrencyEnum::UZS,
                'price_net' => 20000,
                'prices_gross' => '[ { "amount": 30000, "currency": "UZS" }, { "amount": 25, "currency": "USD" } ]'
            ],
            [
                'service_id' => $service3Id,
                'season_id' => $seasonId,
                'car_id' => $carId,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 50000,
                'prices_gross' => '[ { "amount": 100000, "currency": "UZS" }, { "amount": 50, "currency": "USD" } ]'
            ]
        ]);
    }

    private function seedAirportSupplierData(int $supplierId, int $seasonId): void
    {
        DB::table('supplier_airports')->insert([
            [
                'supplier_id' => $supplierId,
                'airport_id' => 1,
            ],
            [
                'supplier_id' => $supplierId,
                'airport_id' => 4,
            ],
        ]);

        $service1Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::CIP_MEETING_IN_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $service2Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'type' => ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $contract1Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::CIP_MEETING_IN_AIRPORT,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $contract2Id = DB::table('supplier_contracts')->insertGetId([
            'supplier_id' => $supplierId,
            'status' => StatusEnum::ACTIVE,
            'service_type' => ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT,
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('supplier_service_contracts')->insert([
            ['contract_id' => $contract1Id, 'service_id' => $service1Id],
            ['contract_id' => $contract2Id, 'service_id' => $service2Id],
        ]);

        DB::table('supplier_airport_prices')->insert([
            [
                'service_id' => $service1Id,
                'season_id' => $seasonId,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 50000,
                'prices_gross' => '[ { "amount": 100000, "currency": "UZS" }, { "amount": 25, "currency": "USD" } ]'
            ],
            [
                'service_id' => $service2Id,
                'season_id' => $seasonId,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 20000,
                'prices_gross' => '[ { "amount": 70000, "currency": "UZS" }, { "amount": 10, "currency": "USD"} ]'
            ]
        ]);
    }
}
