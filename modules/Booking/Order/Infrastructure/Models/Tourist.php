<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Models;

use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Database\Eloquent\Model;

class Tourist extends Model
{
    protected $table = 'order_tourists';

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
