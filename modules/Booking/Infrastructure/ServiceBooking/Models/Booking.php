<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Module\Booking\Infrastructure\Shared\Models\Booking as BaseModel;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read Details\Airport|null $airportDetails
 * @property-read Details\Transfer|null $transferDetails
 * @property-read Details\Other|null $otherDetails
 */
class Booking extends BaseModel
{
    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('bookings.*')
                ->join('orders', 'orders.id', '=', 'bookings.order_id')
                ->join('clients', 'clients.id', '=', 'orders.client_id')
                ->addSelect('clients.name as client_name')
            //@todo приджоинить service_type из услуг поставщика
//                ->join('supplier_services')
                ->join('supplier_transfer_services', 'supplier_transfer_services.id', '=', 'bookings.service_id')
                ->addSelect('supplier_transfer_services.type as service_type');
        });
    }

    public function airportDetails(): HasOne
    {
        return $this->hasOne(Details\Airport::class, 'booking_id');
    }

    public function transferDetails(): HasOne
    {
        return $this->hasOne(Details\Transfer::class, 'booking_id');
    }

    public function otherDetails(): HasOne
    {
        return $this->hasOne(Details\Other::class, 'booking_id');
    }
}
