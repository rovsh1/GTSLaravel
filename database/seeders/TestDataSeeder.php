<?php

namespace Database\Seeders;

use App\Admin\Enums\Contract\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Module\Booking\Application\Admin\HotelBooking\UseCase\System\FillCalculatedPriceCalendar;
use Module\Shared\Enum\Booking\AirportServiceTypeEnum;
use Module\Shared\Enum\Booking\TransferServiceTypeEnum;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;

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

        $service1Id = DB::table('supplier_transfer_services')->insertGetId([
            'supplier_id' => $supplierId,
            'name' => 'Трансфер из аэропорта',
            'type' => TransferServiceTypeEnum::TRANSFER_FROM_AIRPORT,
        ]);

        $service2Id = DB::table('supplier_transfer_services')->insertGetId([
            'supplier_id' => $supplierId,
            'name' => 'Трансфер в аэропорт',
            'type' => TransferServiceTypeEnum::TRANSFER_TO_AIRPORT,
        ]);

        $service3Id = DB::table('supplier_transfer_services')->insertGetId([
            'supplier_id' => $supplierId,
            'name' => 'Аренда авто',
            'type' => TransferServiceTypeEnum::CAR_RENT,
        ]);

        DB::table('supplier_contracts')->insert([
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_type' => ContractServiceTypeEnum::TRANSFER,
                'service_id' => $service1Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_type' => ContractServiceTypeEnum::TRANSFER,
                'service_id' => $service2Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_type' => ContractServiceTypeEnum::TRANSFER,
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
                'prices_gross' => '[ { "amount": 30000, "currency_id": 1 }, { "amount": 25, "currency_id": 3 } ]'
            ],
            [
                'service_id' => $service2Id,
                'season_id' => $seasonId,
                'car_id' => $carId,

                'currency' => CurrencyEnum::UZS,
                'price_net' => 20000,
                'prices_gross' => '[ { "amount": 30000, "currency_id": 1 }, { "amount": 25, "currency_id": 3 } ]'
            ],
            [
                'service_id' => $service3Id,
                'season_id' => $seasonId,
                'car_id' => $carId,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 50000,
                'prices_gross' => '[ { "amount": 100000, "currency_id": 1 }, { "amount": 50, "currency_id": 3 } ]'
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

        $service1Id = DB::table('supplier_airport_services')->insertGetId([
            'supplier_id' => $supplierId,
            'name' => 'CIP Встреча',
            'type' => AirportServiceTypeEnum::MEETING_IN_AIRPORT,
        ]);

        $service2Id = DB::table('supplier_airport_services')->insertGetId([
            'supplier_id' => $supplierId,
            'name' => 'CIP Проводы',
            'type' => AirportServiceTypeEnum::SEEING_IN_AIRPORT,
        ]);

        DB::table('supplier_contracts')->insert([
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_type' => ContractServiceTypeEnum::AIRPORT,
                'service_id' => $service1Id,
                'date_start' => '2023-01-01',
                'date_end' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $supplierId,
                'status' => StatusEnum::ACTIVE,
                'service_type' => ContractServiceTypeEnum::AIRPORT,
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
                'airport_id' => 4,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 50000,
                'prices_gross' => '[ { "amount": 100000, "currency_id": 1 }, { "amount": 25, "currency_id": 3 } ]'
            ],
            [
                'service_id' => $service2Id,
                'season_id' => $seasonId,
                'airport_id' => 4,
                'currency' => CurrencyEnum::UZS,
                'price_net' => 20000,
                'prices_gross' => '[ { "amount": 70000, "currency_id": 1 }, { "amount": 10, "currency_id": 3 } ]'
            ]
        ]);
    }
}
