<?php

namespace GTS\Administrator\Infrastructure\Models;

use GTS\Shared\Custom\Database\Eloquent\HasQuicksearch;
use GTS\Shared\Custom\Database\Eloquent\HasTranslations;
use GTS\Shared\Infrastructure\Models\Model;

class Country extends Model
{
    use HasTranslations, HasQuicksearch;

    protected $quicksearch = ['id', 'name%'];

    protected $translatable = ['name'];

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
