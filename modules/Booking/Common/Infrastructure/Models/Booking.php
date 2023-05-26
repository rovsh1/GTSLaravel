<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Models;

use Custom\Framework\Database\Eloquent\Model;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'order_id',
        'type',
        'status',
        'source',
        'note',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
        'type' => BookingTypeEnum::class,
    ];
}
