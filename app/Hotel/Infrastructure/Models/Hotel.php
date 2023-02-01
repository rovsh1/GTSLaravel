<?php

namespace GTS\Hotel\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;

/**
 * GTS\Hotel\Infrastructure\Models\Hotel
 *
 * @property int $id
 * @property int $city_id
 * @property int $type_id
 * @property string $name
 * @property int|null $rating
 * @property string $address
 * @property string $geolocation
 * @property int $citycenter_distance
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $zipcode
 * @property int $status
 * @property int|null $visible_for
 * @property int $deletion_mark
 * @property \Illuminate\Support\Carbon $updated
 * @property \Illuminate\Support\Carbon $created
 * @property-read \Illuminate\Database\Eloquent\Collection|\GTS\Hotel\Infrastructure\Models\Room[] $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereCitycenterDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereDeletionMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereGeolocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereVisibleFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hotel whereZipcode($value)
 * @mixin \Eloquent
 */
class Hotel extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';
    protected $table = 'hotels';

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }
}
