<?php

namespace App\Shared\Console\Commands;

use App\Admin\Models\Hotel\Image;
use App\Admin\Models\Hotel\RoomImage;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

/**
 * @todo Удалить после прод миграции
 * @deprecated
 */
class MigrateHotelImages extends Command
{
    protected $signature = 'migrate-hotel-images';

    protected $description = '';

    public function handle(FileStorageAdapterInterface $fileStorageAdapter): void
    {
        $this->createImportColumn();

        $q = DB::connection('mysql_old')->table('hotel_images')
            ->join('files', 'files.id', '=', 'hotel_images.image_id')
            ->where('is_imported', 0);

        $progressBar = $this->output->createProgressBar($q->count());
        $progressBar->start();

        $q
            ->addSelect('hotel_images.hotel_id')
            ->addSelect('hotel_images.image_id')
            ->addSelect('hotel_images.index')
//            ->addSelect('hotel_images.is_main')
            ->addSelect('files.guid')
            ->addSelect('files.name');
        foreach ($q->cursor() as $r) {
            $this->uploadImage(
                $fileStorageAdapter,
                $r,
                $this->getImageContent($r->guid)
            );

            $progressBar->advance();
        }

        $progressBar->finish();
    }

    private function getImageContent(string $guid): string
    {
        return file_get_contents("https://www.gotostans.com/file/$guid/");
    }

    private function uploadImage(FileStorageAdapterInterface $fileStorageAdapter, $r, $content): void
    {
        $fileDto = $fileStorageAdapter->create(
            $r->name,
            $content
        );

        DB::transaction(function () use ($r, $fileDto) {
            $hotelImage = Image::create([
                'hotel_id' => $r->hotel_id,
                'title' => $fileDto->name,
                'index' => $r->index,
                'file' => $fileDto->guid,
            ]);

            $roomsIds = DB::connection('mysql_old')->table('hotel_image_rooms')
                ->where('image_id', $r->image_id)
                ->pluck('room_id');

            foreach ($roomsIds as $roomId) {
                RoomImage::insert([
                    'room_id' => $roomId,
                    'image_id' => $hotelImage->id,
                    'index' => RoomImage::getNextIndexByRoomId($roomId)
                ]);
            }

            DB::connection('mysql_old')->table('hotel_images')
                ->where('image_id', $r->image_id)
                ->update([
                    'is_imported' => 1
                ]);
        });
    }

    private function createImportColumn(): void
    {
        if (Schema::connection('mysql_old')->hasColumn('hotel_images', 'is_imported')) {
            return;
        }

        Schema::connection('mysql_old')->table('hotel_images', function (Blueprint $blueprint) {
            $blueprint->boolean('is_imported')->default(0);
        });
    }
}
