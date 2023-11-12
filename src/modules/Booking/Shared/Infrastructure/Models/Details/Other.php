<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Details;

use Sdk\Module\Database\Eloquent\Model;

class Other extends Model
{
    protected $table = 'booking_other_details';

    protected $fillable = [
        'booking_id',
        'service_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
