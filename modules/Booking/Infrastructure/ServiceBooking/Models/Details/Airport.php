<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsModelInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Airport extends Model implements DetailsModelInterface
{
    protected $table = 'booking_airport_details';

    protected $fillable = [
        'booking_id',
        'date',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'service_type' => ServiceTypeEnum::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('booking_airport_details.*')
                ->join(
                    'supplier_airport_services',
                    'supplier_airport_services.id',
                    '=',
                    'booking_airport_details.service_id'
                )
                ->addSelect('supplier_airport_services.type as service_type');
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
