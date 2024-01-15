<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Models\Price;

use Shared\Models\Model;

class Group extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_price_groups';

    protected $fillable = [
        'rate_id',
        'guests_count',
        'is_resident',
    ];
}
