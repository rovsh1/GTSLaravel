<?php

namespace GTS\Administrator\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;

class Country extends Model
{
    protected $table = 'r_countries';

    protected $fillable = [
        'name',
        'flag',
        'default',
    ];

    protected $casts = [
        'default' => 'bool'
    ];
}
