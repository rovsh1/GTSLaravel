<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;

class Country extends Model
{
    use HasQuicksearch;
    use HasTranslations;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];
    protected array $translatable = ['name'];

    protected $table = 'r_countries';

    protected $fillable = [
        'name',
        'flag',
        'default',
        'phone_code',
        'language'
    ];

    protected $casts = [
        'default' => 'bool'
    ];

    public static function booted()
    {
        static::addGlobalTranslationScope();
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
