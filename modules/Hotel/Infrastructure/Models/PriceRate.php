<?php

namespace Module\Hotel\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;

class PriceRate extends Model
{
    use HasTranslations;

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'hotel_price_rates';

    protected array $translatable = ['name', 'text'];

    protected $fillable = [
        'hotel_id',
        'name'
    ];

    public static function booted()
    {
        static::addGlobalTranslationScope();
    }
}
