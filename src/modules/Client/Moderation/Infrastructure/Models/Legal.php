<?php

namespace Module\Client\Moderation\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class Legal extends Model
{
    protected $table = 'client_legals';

    protected $fillable = [
        'name',
        'client_id',
        'city_id',
        'industry_id',
        'address',
        'requisites',
    ];

}
