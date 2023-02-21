<?php

namespace App\Admin\Models;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;

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
