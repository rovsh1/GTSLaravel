<?php

namespace Module\Hotel\Infrastructure\Models;

use Module\HotelOld\Infrastructure\Models\Room;
use Sdk\Module\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotels';

    protected $fillable = [
        'city_id',
        'type_id',
        'status',
        'visibility',
        'rating',
        'name',
        'zipcode',
        'address',
        'address_lat',
        'address_lon',
        'city_distance',
        'markup_settings',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }
}
