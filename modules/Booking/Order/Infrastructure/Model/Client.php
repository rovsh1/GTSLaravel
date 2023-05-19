<?php

namespace Module\Booking\Hotel\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    //@todo оставить только нужные поля для модуля
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
