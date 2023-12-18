<?php

namespace App\Admin\Models;

use App\Shared\Support\Facades\FileStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sdk\Shared\Dto\FileDto;

/**
 * App\Admin\Models\Hotel\RoomImage
 *
 * @property int $image_id
 * @property int $room_id
 * @property FileDto|null $file
 * @property string $title
 * @property int $hotel_id
 * @property int $index
 * @method static Builder|RoomImage newModelQuery()
 * @method static Builder|RoomImage newQuery()
 * @method static Builder|RoomImage query()
 * @method static Builder|RoomImage whereHotelId(int $id)
 * @method static Builder|RoomImage whereImageId($value)
 * @method static Builder|RoomImage whereRoomId(int $value)
 * @method static Builder|RoomImage orderByIndex()
 * @mixin \Eloquent
 */
class RoomImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = null;

    protected $table = 'hotel_room_images';

    protected $fillable = [
        'room_id',
        'image_id',
        'index',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_room_images.*')
                ->join('hotel_images', 'hotel_images.id', '=', 'hotel_room_images.image_id')
                ->addSelect('hotel_images.file as file_guid')
                ->addSelect('hotel_images.hotel_id as hotel_id')
                ->addSelect('hotel_images.title as title');
        });
    }

    public function scopeWhereHotelId(Builder $builder, int $id): void
    {
        $builder->where('hotel_images.hotel_id', $id);
    }

    public function scopeOrderByIndex(Builder $builder): void
    {
        $builder->orderBy('hotel_room_images.index');
    }

    public function file(): Attribute
    {
        return Attribute::get(fn() => FileStorage::find($this->file_guid));
    }

    public static function getNextIndexByRoomId(int $roomId): int
    {
        $index = DB::select(
            'SELECT MAX(`index`) as `index` FROM `hotel_room_images` WHERE `room_id`= :room_id',
            ['room_id' => $roomId]
        );
        $index = $index[0];
        return $index->index !== null ? $index->index + 1 : 0;
    }
}
