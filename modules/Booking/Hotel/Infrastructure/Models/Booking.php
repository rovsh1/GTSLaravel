<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Booking as BaseModel;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Booking extends BaseModel
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'hotels.name%'];

    protected $attributes = [
        'type' => BookingTypeEnum::HOTEL,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereType(BookingTypeEnum::HOTEL);
        });
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

    public function scopeWhereType(Builder $builder, BookingTypeEnum $type): void
    {
        $builder->where($this->getTable() . '.type', $type);
    }

    public function scopeWithDetails(Builder $builder): void
    {
        $builder->addSelect('bookings.*')
            ->join('orders', 'orders.id', '=', 'bookings.order_id')
            ->join('clients', 'clients.id', '=', 'orders.client_id')
            ->addSelect('clients.name as client_name')
            ->join('booking_hotel_details', 'bookings.id', '=', 'booking_hotel_details.booking_id')
            ->addSelect('booking_hotel_details.date_start as date_start')
            ->addSelect('booking_hotel_details.date_end as date_end')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->join('hotels', 'hotels.id', '=', 'booking_hotel_details.hotel_id')
            ->addSelect('hotels.name as hotel_name')
            ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
            ->joinTranslatable('r_cities', 'name as city_name');
    }
}
