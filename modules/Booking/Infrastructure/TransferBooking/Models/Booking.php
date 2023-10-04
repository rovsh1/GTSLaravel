<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\TransferBooking\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Infrastructure\Shared\Models\Booking as BaseModel;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

/**
 * Module\Booking\Transfer\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read int[] $guest_ids
 */
class Booking extends BaseModel
{
    use HasQuicksearch, SoftDeletes;

    protected $attributes = [
        'type' => BookingTypeEnum::TRANSFER,
    ];

    private bool $isDetailsJoined = false;

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereType(BookingTypeEnum::TRANSFER);
        });
    }

    public function scopeWhereType(Builder $builder, BookingTypeEnum $type): void
    {
        $builder->where($this->getTable() . '.type', $type);
    }

    public function scopeApplyCriteria(Builder $query, array $criteria): void
    {
        if (isset($criteria['quicksearch'])) {
            $query->quicksearch($criteria['quicksearch']);
            unset($criteria['quicksearch']);
        }

        foreach ($criteria as $k => $v) {
            $scopeName = \Str::camel($k);
            $scopeMethod = 'where' . ucfirst($scopeName);
            $hasScope = $this->hasNamedScope($scopeMethod);
            if ($hasScope) {
                $query->$scopeMethod($v);
                continue;
            }
            $query->where($k, $v);
        }
    }

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
            ->join('supplier_transfer_services', 'supplier_transfer_services.id', '=','booking_transfer_details.service_id')
            ->addSelect('supplier_transfer_services.name as service_name')
;
    }
}
