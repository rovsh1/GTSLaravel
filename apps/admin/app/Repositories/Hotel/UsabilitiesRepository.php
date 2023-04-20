<?php

namespace App\Admin\Repositories\Hotel;

use App\Admin\Support\Repository\RepositoryInterface;
use Illuminate\Support\Facades\DB;

class UsabilitiesRepository implements RepositoryInterface
{
    public function update(int $hotelId, array $usabilitiesData): void
    {
        DB::transaction(function () use ($hotelId, $usabilitiesData) {
            DB::table('hotel_usabilities')
                ->where('hotel_id', $hotelId)
                ->delete();

            DB::table('hotel_usabilities')->insert($usabilitiesData);
        });
    }
}
