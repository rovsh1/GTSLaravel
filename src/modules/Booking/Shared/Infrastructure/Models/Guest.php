<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\GenderEnum;

class Guest extends Model
{
    protected $table = 'order_guests';

    protected $fillable = [
        'order_id',
        'name',
        'country_id',
        'gender',
        'is_adult',
        'age',
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        'is_adult' => 'boolean',
    ];
}
