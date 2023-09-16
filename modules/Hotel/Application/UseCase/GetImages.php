<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase;

use App\Admin\Models\Hotel\Image;
use Illuminate\Support\Collection;
use Module\Hotel\Application\Dto\ImageDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetImages implements UseCaseInterface
{
    public function execute(int $hotelId, int $roomId = null): Collection
    {
        return Image::whereHotelId($hotelId)
            ->orderBy('index')
            ->get()
            ->map(fn($r) => new ImageDto(
                $r->id,
                $r->is_main,
                $r->index,
                $r->file
            ));
    }
}
