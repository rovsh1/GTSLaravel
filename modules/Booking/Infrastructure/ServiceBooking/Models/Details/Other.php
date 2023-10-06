<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Database\Eloquent\Model;

class Other extends Model
{
    protected $table = 'booking_other_details';

    protected $fillable = [
        'booking_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
