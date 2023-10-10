<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsModelInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Transfer extends Model implements DetailsModelInterface
{
    protected $table = 'booking_transfer_details';

    protected $fillable = [
        'booking_id',
        'date_start',
        'date_end',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'service_type' => ServiceTypeEnum::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('booking_transfer_details.*')
                ->join('bookings', 'bookings.id', '=', 'booking_transfer_details.booking_id')
                ->join('supplier_transfer_services', 'supplier_transfer_services.id', '=', 'bookings.service_id')
                ->addSelect('supplier_transfer_services.type as service_type');
        });
    }

    public function bookingId(): int
    {
        return $this->booking_id;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->service_type;
    }
}
