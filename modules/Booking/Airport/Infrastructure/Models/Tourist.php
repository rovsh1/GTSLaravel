<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Database\Eloquent\Model;

class Tourist extends Model
{
    protected $table = 'order_tourists';

    protected $fillable = [];

    protected $casts = [
        'gender' => GenderEnum::class,
        'is_adult' => 'boolean',
    ];
}
