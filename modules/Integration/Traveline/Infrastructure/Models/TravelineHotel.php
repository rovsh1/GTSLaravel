<?php

namespace Module\Integration\Traveline\Infrastructure\Models;

use Module\Shared\Infrastructure\Models\Model;

/**
 * GTS\Integration\Traveline\Infrastructure\Models\TravelineHotel
 *
 * @property int $id
 * @property int $hotel_id
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineHotel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineHotel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineHotel query()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineHotel whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelineHotel whereId($value)
 * @mixin \Eloquent
 */
class TravelineHotel extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'traveline_hotels';

    protected $fillable = [
        'hotel_id'
    ];

}
