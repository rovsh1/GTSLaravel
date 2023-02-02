<?php

namespace GTS\Hotel\Infrastructure\Models;

use GTS\Shared\Custom\Database\Eloquent\HasTranslations;
use GTS\Shared\Infrastructure\Models\Model;

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
}
