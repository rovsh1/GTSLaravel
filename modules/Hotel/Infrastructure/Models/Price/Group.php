<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Models\Price;

use Module\Shared\Infrastructure\Models\Model;

class Group extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_price_groups';

    protected $fillable = [
        'rate_id',
        'guests_number',
        'is_resident',
    ];
}
