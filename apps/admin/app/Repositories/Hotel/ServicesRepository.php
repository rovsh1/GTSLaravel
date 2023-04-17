<?php

namespace App\Admin\Repositories\Hotel;

use App\Admin\Support\Repository\RepositoryInterface;

class ServicesRepository implements RepositoryInterface
{
    public function update(int $hotelId, array $servicesData): void
    {
        \DB::transaction(function () use ($hotelId, $servicesData) {
            \DB::table('hotel_services')
                ->where('hotel_id', $hotelId)
                ->delete();

            \DB::table('hotel_services')->insert($servicesData);
        });
    }
}
