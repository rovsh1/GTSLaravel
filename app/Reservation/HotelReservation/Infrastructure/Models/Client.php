<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;

class Client extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT =  'updated';

    protected $table = 'clients';

    protected $fillable = [
        'city_id',
        'administrator_id',
        'currency_id',
        'price_type',
        'type',
        'name',
        'description',
        'status',
        'deletion_mark',
    ];

}
