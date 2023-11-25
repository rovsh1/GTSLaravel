<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use App\Admin\Models\Hotel\Image;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class DeleteImage implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $hotelId, int $imageId): void
    {
        $image = Image::find($imageId);

        DB::transaction(function () use ($image) {
            $guid = $image->file?->guid;
            $image->delete();
            if ($guid) {
                $this->fileStorageAdapter->delete($guid);
            }
        });
    }
}
