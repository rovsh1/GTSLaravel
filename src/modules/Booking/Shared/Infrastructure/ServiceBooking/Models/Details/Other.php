<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\ServiceBooking\Models\Details;

use Module\Booking\Shared\Infrastructure\ServiceBooking\Factory\DetailsModelInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Other extends Model implements DetailsModelInterface
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

    public function bookingId(): int
    {
        return $this->booking_id;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return ServiceTypeEnum::OTHER_SERVICE;
    }
}