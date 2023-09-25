<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Booking as BaseModel;

/**
 * Module\Booking\Airport\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read int[] $guest_ids
 */
class Booking extends BaseModel
{
    protected $attributes = [
        'type' => BookingTypeEnum::AIRPORT,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereType(BookingTypeEnum::AIRPORT);
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
        $builder->addSelect('bookings.*')
            ->join('orders', 'orders.id', '=', 'bookings.order_id')
            ->join('clients', 'clients.id', '=', 'orders.client_id')
            ->addSelect('clients.name as client_name')
            ->join('booking_airport_details', 'bookings.id', '=', 'booking_airport_details.booking_id')
            ->addSelect('booking_airport_details.date as date')
            ->join('r_airports', 'r_airports.id', '=', 'booking_airport_details.airport_id')
            ->addSelect('r_airports.name as airport_name')
            ->join('r_cities', 'r_cities.id', '=', 'r_airports.city_id')
            ->joinTranslatable('r_cities', 'name as city_name')
            ->join('supplier_airport_services', 'supplier_airport_services.id', '=','booking_airport_details.service_id')
            ->addSelect('supplier_airport_services.name as service_name');
    }

    public function guestIds(): Attribute
    {
        return Attribute::get(
            fn() => $this->guests()->pluck('guest_id')->toArray()
        );
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(
            Guest::class,
            'booking_airport_guests',
            'booking_airport_id',
            'guest_id',
        );
    }
}
