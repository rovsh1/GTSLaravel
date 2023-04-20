<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Files\HotelImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Admin\Models\Hotel\Image
 *
 * @property int $id
 * @property int $hotel_id
 * @property string $image_id
 * @property int $index
 * @property string|null $title
 * @property \Custom\Framework\Support\DateTime|null $created_at
 * @property \Custom\Framework\Support\DateTime|null $updated_at
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
        'image_id',
        'index',
        'title',
    ];

    public function file(): Attribute
    {
        return Attribute::get(fn() => HotelImage::find($this->image_id));
    }
}
