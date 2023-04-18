<?php

namespace App\Admin\Repositories\Hotel;

use App\Admin\Support\Repository\RepositoryInterface;

class LandmarkRepository implements RepositoryInterface
{
    public function create(int $hotelId, int $landmarkId, int $distance): void
    {
        \DB::table('hotel_landmark')->updateOrInsert(
            ['hotel_id' => $hotelId, 'landmark_id' => $landmarkId],
            ['hotel_id' => $hotelId, 'landmark_id' => $landmarkId, 'distance' => $distance],
        );
    }

    public function delete(int $hotelId, int $landmarkId): void
    {
        \DB::table('hotel_landmark')
            ->where('hotel_id', $hotelId)
            ->where('landmark_id', $landmarkId)
            ->delete();
    }
}
