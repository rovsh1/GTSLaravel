<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase;

use App\Admin\Models\Hotel\Image;
use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

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
            $guid = $image->file_guid;
            $image->delete();
            $this->fileStorageAdapter->delete($guid);
        });
    }
}
