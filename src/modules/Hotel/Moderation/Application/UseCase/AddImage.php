<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use App\Admin\Models\Hotel\Image;
use App\Admin\Models\Hotel\RoomImage;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Moderation\Application\RequestDto\AddImageRequestDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class AddImage implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(AddImageRequestDto $request): void
    {
        DB::transaction(function () use ($request) {
            $fileDto = $this->fileStorageAdapter->create(
                $request->uploadedFile->name,
                $request->uploadedFile->contents
            );

            $index = Image::getNextIndexByHotelId($request->hotelId);

            $hotelImage = Image::create([
                'hotel_id' => $request->hotelId,
                'title' => $fileDto->name,
                'index' => $index,
                'file' => $fileDto->guid,
            ]);

            if ($request->roomId !== null) {
                RoomImage::insert([
                    'room_id' => $request->roomId,
                    'image_id' => $hotelImage->id,
                    'index' => RoomImage::getNextIndexByRoomId($request->roomId)
                ]);
            }
        });
    }
}
