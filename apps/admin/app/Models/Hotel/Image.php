<?php

namespace App\Admin\Models\Hotel;

use App\Shared\Support\Facades\FileStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Support\DateTime;

/**
 * App\Admin\Models\Hotel\Image
 *
 * @property int $id
 * @property int $hotel_id
 * @property string $file_guid
 * @property bool $is_main
 * @property int $index
 * @property string|null $title
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @method static Builder|Image whereCreatedAt($value)
 * @method static Builder|Image whereHotelId($value)
 * @method static Builder|Image whereId($value)
 * @method static Builder|Image whereImageId($value)
 * @method static Builder|Image whereIndex($value)
 * @method static Builder|Image whereTitle($value)
 * @method static Builder|Image whereUpdatedAt($value)
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
        return Attribute::get(fn() => FileStorage::find($this->file_guid));
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
