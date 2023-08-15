<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Files\HotelImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Admin\Models\Hotel\Image
 *
 * @property int $id
 * @property int $hotel_id
 * @property string $file_guid
 * @property bool $is_main
 * @property int $index
 * @property string|null $title
 * @property \Sdk\Module\Support\DateTime|null $created_at
 * @property \Sdk\Module\Support\DateTime|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    use HasFactory;

    protected $table = 'hotel_images';

    protected $fillable = [
        'hotel_id',
        'file_guid',
        'index',
        'title',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function file(): Attribute
    {
        return Attribute::get(fn() => HotelImage::find($this->file_guid));
    }

    public static function getNextIndexByHotelId(int $hotelId): int
    {
        $index = DB::select(
            'SELECT MAX(`index`) as `index` FROM `hotel_images` WHERE `hotel_id`= :hotel_id',
            ['hotel_id' => $hotelId]
        );
        $index = $index[0];
        return $index->index !== null ? $index->index + 1 : 0;
    }
}
