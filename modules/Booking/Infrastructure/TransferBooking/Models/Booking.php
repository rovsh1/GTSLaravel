<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\TransferBooking\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Infrastructure\Shared\Models\Booking as BaseModel;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read int[] $guest_ids
 */
class Booking extends BaseModel
{
    private bool $isDetailsJoined = false;

    protected static function booted() {}

    public function scopeWithDetails(Builder $builder): void
    {
        if ($this->isDetailsJoined) {
            return;
        }
        $this->isDetailsJoined = true;
        $builder->addSelect('bookings.*')
            ->join('orders', 'orders.id', '=', 'bookings.order_id')
            ->join('clients', 'clients.id', '=', 'orders.client_id')
            ->addSelect('clients.name as client_name')
            ->join('booking_transfer_details', 'bookings.id', '=', 'booking_transfer_details.booking_id')
            ->join('r_cities', 'r_cities.id', '=', 'booking_transfer_details.city_id')
            ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
            ->joinTranslatable('r_countries', 'name as country_name')
            ->joinTranslatable('r_cities', 'name as city_name')
            ->join(
                'supplier_services',
                'supplier_services.id',
                '=',
                'booking_transfer_details.service_id'
            )
            ->addSelect('supplier_services.title as service_name');
    }
}
