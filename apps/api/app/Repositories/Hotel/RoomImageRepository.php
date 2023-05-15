<?php

namespace App\Api\Repositories\Hotel;

use App\Admin\Models\Hotel\RoomImage;
use Illuminate\Support\Collection;

class RoomImageRepository
{
    /**
     * @param int $hotelId
     * @param int $roomId
     * @return Collection<RoomImage>|RoomImage[]
     */
    public function get(int $hotelId, int $roomId): Collection
    {
        return RoomImage::whereHotelId($hotelId)
            ->whereRoomId($roomId)
            ->orderByIndex()
            ->get()
            ->append(['file']);
    }

    public function create(int $imageId, int $roomId): void
    {
        RoomImage::updateOrCreate(
            ['room_id' => $roomId, 'image_id' => $imageId],
            ['room_id' => $roomId, 'image_id' => $imageId]
        );
    }

    public function getImageRoomIds(int $hotelId, int $imageId): array
    {
        return RoomImage::whereHotelId($hotelId)
            ->whereImageId($imageId)
            ->get()
            ->pluck('room_id')
            ->all();
    }

    public function delete(int $imageId, int $roomId): void
    {
        RoomImage::query()->where('room_id', $roomId)->where('image_id', $imageId)->delete();
    }
}
