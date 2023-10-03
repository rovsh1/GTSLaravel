<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Models;

use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
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
