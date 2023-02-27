<?php

namespace App\Admin\Models;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;

class City extends Model
{
    use HasTranslations, HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name', 'text'];

    protected $table = 'r_cities';

    protected $fillable = [
        'name',
        'country_id',
        'text'
    ];

    public static function booted()
    {
        static::addGlobalTranslationScope();
    }
}
