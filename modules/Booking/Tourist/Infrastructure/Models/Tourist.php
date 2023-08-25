<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Infrastructure\Models;

use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Database\Eloquent\Model;

class Tourist extends Model
{
    protected $table = 'tourists';

    protected $fillable = [
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
