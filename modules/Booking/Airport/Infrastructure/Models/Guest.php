<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Module\Shared\Domain\ValueObject\GenderEnum;
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
