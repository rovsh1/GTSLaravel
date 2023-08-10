<?php

namespace App\Api\Repositories\Hotel;

use App\Admin\Files\HotelImage;
use App\Admin\Models\Hotel\Image;
use App\Admin\Models\Hotel\RoomImage;
use App\Core\Support\Facades\FileAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImageRepository
{
    /**
     * @param int $hotelId
     * @return Collection<Image>|Image[]
     */
    public function get(int $hotelId): Collection
    {
        return Image::whereHotelId($hotelId)
            ->orderBy('index')
            ->get()
            ->append(['file']);
    }

    public function create(UploadedFile $file, int $hotelId, ?int $roomId = null, ?string $title = null): int
    {
        return DB::transaction(function () use ($file, $hotelId, $roomId, $title) {
            $imageFile = HotelImage::create(
                $hotelId,
                $file->getClientOriginalName(),
                $file->getContent()
            );

            $index = Image::getNextIndexByHotelId($hotelId);

            $hotelImage = Image::create([
                'hotel_id' => $hotelId,
                'title' => $title,
                'index' => $index,
                'file_guid' => $imageFile->guid(),
            ]);

            if ($roomId !== null) {
                RoomImage::insert([
                    'room_id' => $roomId,
                    'image_id' => $hotelImage->id,
                    'index' => RoomImage::getNextIndexByRoomId($roomId)
                ]);
            }

            return $hotelImage->id;
        });
    }

    public function delete(Image $image): void
    {
        DB::transaction(function () use ($image) {
            $guid = $image->file_guid;
            $image->delete();
            FileAdapter::delete($guid);
        });
    }
}
