<?php

namespace Module\HotelOld\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

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
