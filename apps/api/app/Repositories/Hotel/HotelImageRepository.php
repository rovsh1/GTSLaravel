<?php

namespace App\Api\Repositories\Hotel;

use App\Admin\Files\HotelImage;
use App\Admin\Models\Hotel\Image;
use App\Core\Support\Facades\FileAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HotelImageRepository
{
    /**
     * @param int $hotelId
     * @return Collection<Image>|Image[]
     */
    public function get(int $hotelId): Collection
    {
        return Image::whereHotelId($hotelId)
            ->get()
            ->append(['file']);
    }

    public function create(UploadedFile $file, int $hotelId, ?int $roomId = null, ?string $title = null): int
    {
        return DB::transaction(function () use ($file, $hotelId, $roomId, $title) {
            $image = HotelImage::create(
                $hotelId,
                $file->getClientOriginalName(),
                $file->getContent()
            );

            $index = Image::getNextIndexByHotelId($hotelId);

            $hotelImage = Image::create([
                'hotel_id' => $hotelId,
                'title' => $title,
                'index' => $index,
                'file_guid' => $image->guid(),
            ]);

            if ($roomId !== null) {
                DB::table('hotel_room_images')->insertGetId([
                    'room_id' => $roomId,
                    'image_id' => $hotelImage->id,
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
