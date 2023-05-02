<?php

namespace Module\Hotel\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;
use Module\HotelOld\Infrastructure\Models\Room;

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

    protected $casts = [
        'markup_settings' => 'array'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }
}
