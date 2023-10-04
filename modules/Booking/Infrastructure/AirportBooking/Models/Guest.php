<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\AirportBooking\Models;

use Module\Shared\Enum\GenderEnum;
use Sdk\Module\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'order_guests';

    protected $fillable = [];

    protected $casts = [
        'gender' => GenderEnum::class,
        'is_adult' => 'boolean',
    ];
}