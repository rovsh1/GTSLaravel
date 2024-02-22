<?php

namespace App\Hotel\Repository;

use Illuminate\Support\Facades\DB;

class ServicesRepository
{
    public function update(int $hotelId, array $servicesData): void
    {
        DB::transaction(function () use ($hotelId, $servicesData) {
            DB::table('hotel_services')
                ->where('hotel_id', $hotelId)
                ->delete();

            DB::table('hotel_services')->insert($servicesData);
        });
    }
}
