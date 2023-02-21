<?php

namespace App\Admin\Models;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;

class City extends Model
{
    use HasTranslations, HasQuicksearch;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name'];

    protected $table = 'r_cities';

    protected $fillable = [
        'name',
        'country_id',
    ];
}
