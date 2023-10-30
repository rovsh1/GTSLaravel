<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsModelInterface;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Hotel extends Model implements DetailsModelInterface
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

    public function bookingId(): int
    {
        return $this->booking_id;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::HOTEL_BOOKING;
    }
}
