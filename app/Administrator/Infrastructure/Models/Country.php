<?php

namespace GTS\Administrator\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;

use GTS\Shared\Infrastructure\Models\Model;

class Country extends Model
{
    use HasTranslations, HasQuicksearch;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name'];

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
