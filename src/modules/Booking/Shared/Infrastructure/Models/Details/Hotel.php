<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Details;

use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'booking_hotel_details';

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'date_start',
        'date_end',
        'nights_count',
        'quota_processing_method',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'quota_processing_method' => QuotaProcessingMethodEnum::class
    ];

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::HOTEL_BOOKING;
    }
}
