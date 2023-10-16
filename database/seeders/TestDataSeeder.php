<?php

namespace Database\Seeders;

use App\Admin\Enums\Contract\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Booking\Application\Admin\HotelBooking\UseCase\System\FillCalculatedPriceCalendar;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('prod', 'production') || DB::table('hotel_price_groups')->exists()) {
            return;
        }

        DB::table('hotel_calculated_price_calendar')->delete();
        DB::table('hotel_season_price_calendar')->delete();
        DB::table('hotel_season_prices')->delete();
        DB::table('hotel_price_groups')->delete();

        DB::table('hotel_price_groups')
            ->insert([
                ['id' => 1, 'rate_id' => 251, 'guests_count' => 1, 'is_resident' => 1],
                ['id' => 2, 'rate_id' => 251, 'guests_count' => 1, 'is_resident' => 0],
                ['id' => 3, 'rate_id' => 251, 'guests_count' => 2, 'is_resident' => 1],
                ['id' => 4, 'rate_id' => 251, 'guests_count' => 2, 'is_resident' => 0],
                ['id' => 5, 'rate_id' => 251, 'guests_count' => 3, 'is_resident' => 1],
                ['id' => 6, 'rate_id' => 251, 'guests_count' => 3, 'is_resident' => 0],
            ]);

        DB::table('hotel_season_prices')
            ->insert([
                ['season_id' => 1197, 'group_id' => 1, 'room_id' => 233, 'price' => 500000],
                ['season_id' => 1197, 'group_id' => 2, 'room_id' => 233, 'price' => 600000],
                ['season_id' => 1197, 'group_id' => 3, 'room_id' => 233, 'price' => 650000],
                ['season_id' => 1197, 'group_id' => 4, 'room_id' => 233, 'price' => 700000],

                ['season_id' => 1197, 'group_id' => 1, 'room_id' => 238, 'price' => 1000000],
                ['season_id' => 1197, 'group_id' => 2, 'room_id' => 238, 'price' => 2000000],
                ['season_id' => 1197, 'group_id' => 3, 'room_id' => 238, 'price' => 1000000],
                ['season_id' => 1197, 'group_id' => 4, 'room_id' => 238, 'price' => 2000000],
                ['season_id' => 1197, 'group_id' => 5, 'room_id' => 238, 'price' => 1000000],
                ['season_id' => 1197, 'group_id' => 6, 'room_id' => 238, 'price' => 2000000],
            ]);

        app(FillCalculatedPriceCalendar::class)->execute(61);

        $testSupplierId = DB::table('suppliers')->insertGetId([
            'name' => 'Sixt',
            'currency' => CurrencyEnum::UZS,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $supplierSeasonId = DB::table('supplier_seasons')->insertGetId([
            'supplier_id' => $testSupplierId,
            'number' => '2023',
            'date_start' => '2023-01-01',
            'date_end' => '2023-12-31',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('supplier_cities')->insert([
            [
                'supplier_id' => $testSupplierId,
                'city_id' => 1,
            ],
            [
                'supplier_id' => $testSupplierId,
                'city_id' => 4,
            ],
        ]);

        DB::table('supplier_requisites')->insert([
            ['supplier_id' => $testSupplierId, 'inn' => '12345678', 'director_full_name' => 'John Doe']
        ]);

        $this->seedTransferSupplierData($testSupplierId, $supplierSeasonId);
        $this->seedAirportSupplierData($testSupplierId, $supplierSeasonId);
    }

    private function seedTransferSupplierData(int $supplierId, int $seasonId): void
    {
        $carId = DB::table('supplier_cars')->insertGetId([
            'supplier_id' => $supplierId,
            'car_id' => 1,
        ]);

        $service1Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'title' => 'Трансфер из аэропорта Ташкента',
            'type' => ServiceTypeEnum::TRANSFER_FROM_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $service2Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'title' => 'Трансфер в аэропорта Ташкента',
            'type' => ServiceTypeEnum::TRANSFER_TO_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $service3Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'title' => 'Аренда авто',
            'type' => ServiceTypeEnum::CAR_RENT,
            'data' => null,
        ]);

        DB::table('supplier_contracts')->insert([
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_id' => $service1Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_id' => $service2Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_id' => $service3Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ]
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
            'title' => 'CIP Встреча',
            'type' => ServiceTypeEnum::CIP_IN_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        $service2Id = DB::table('supplier_services')->insertGetId([
            'supplier_id' => $supplierId,
            'title' => 'CIP Проводы',
            'type' => ServiceTypeEnum::CIP_IN_AIRPORT,
            'data' => json_encode(['airportId' => 1])
        ]);

        DB::table('supplier_contracts')->insert([
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_id' => $service1Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_id' => $service2Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ]
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
