<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Files\HotelImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Admin\Models\Hotel\RoomImage
 *
 * @property int $image_id
 * @property int $room_id
 * @property string $file_guid
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

    protected $table = 'hotel_room_images';

    protected $fillable = [
        'room_id',
        'image_id',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_room_images.*')
                ->join('hotel_images', 'hotel_images.id', '=', 'hotel_room_images.image_id')
                ->addSelect('hotel_images.file_guid as file_guid')
                ->addSelect('hotel_images.hotel_id as hotel_id')
                ->addSelect('hotel_images.title as title')
                ->addSelect('hotel_images.index as index');
        });
    }

    public function scopeWhereHotelId(Builder $builder, int $id): void
    {
        $builder->where('hotel_images.hotel_id', $id);
    }

    public function scopeOrderByIndex(Builder $builder): void
    {
        $builder->orderBy('hotel_images.index');
    }

    public function file(): Attribute
    {
        return Attribute::get(fn() => HotelImage::find($this->file_guid));
    }
}
