<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Models;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

abstract class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'order_id',
        'type',
        'status',
        'source',
        'creator_id',
        'price',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
        'type' => BookingTypeEnum::class,
        'price' => 'array',
    ];
}
