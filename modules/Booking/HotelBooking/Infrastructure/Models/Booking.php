<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Models;

use App\Admin\Support\View\Form\ValueObject\NumRangeValue;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Booking as BaseModel;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Booking extends BaseModel
{
    use HasQuicksearch, SoftDeletes;

    protected array $quicksearch = ['id', 'hotels.name%'];

    private bool $isDetailsJoined = false;

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

    public function scopeWhereCityId(Builder $builder, int $id): void
    {
        $builder->withDetails()->where('hotels.city_id', $id);
    }

    public function scopeWhereHotelId(Builder $builder, int $id): void
    {
        $builder->withDetails()->where('booking_hotel_details.hotel_id', $id);
    }

    public function scopeWhereClientId(Builder $builder, int $id): void
    {
        $builder->withDetails()->where('orders.client_id', $id);
    }

    public function scopeWhereStatus(Builder $builder, int $id): void
    {
        $builder->where('bookings.status', $id);
    }

    public function scopeWhereSource(Builder $builder, string $source): void
    {
        $builder->where('bookings.source', $source);
    }

    public function scopeWhereStartPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->withDetails()->whereBetween('booking_hotel_details.date_start', [
            $period->getStartDate()->startOfDay(),
            $period->getEndDate()->endOfDay(),
        ]);
    }

    public function scopeWhereEndPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->withDetails()->whereBetween('booking_hotel_details.date_end', [
            $period->getStartDate()->startOfDay(),
            $period->getEndDate()->endOfDay(),
        ]);
    }

    public function scopeWhereCreatedPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->withDetails()->whereBetween('bookings.created_at', [
            $period->getStartDate()->startOfDay(),
            $period->getEndDate()->endOfDay(),
        ]);
    }

    public function scopeWhereManagerId(Builder $builder, int $id): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($id) {
            $query->select(\DB::raw(1))
                ->from('administrator_bookings as adm')
                ->whereColumn('adm.booking_id', '=', 'bookings.id')
                ->where('administrator_id', $id);
        });
    }

    public function scopeWhereHotelRoomId(Builder $builder, int $id): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($id) {
            $query->select(\DB::raw(1))
                ->from('booking_hotel_rooms as r')
                ->whereColumn('r.booking_id', '=', 'bookings.id')
                ->where('hotel_room_id', $id);
        });
    }

    public function scopeWhereGuestsCount(Builder $builder, ?NumRangeValue $numRange): void
    {
        if ($numRange === null) {
            return;
        }
        $builder->whereExists(function (QueryBuilder $query) use ($numRange) {
            $query->select(\DB::raw(1))
                ->from('booking_hotel_rooms as r')
                ->whereColumn('r.booking_id', '=', 'bookings.id')
                ->havingRaw("SUM(`guests_count` between {$numRange->from} and {$numRange->to})");
        });
    }

    public function scopeWhereType(Builder $builder, BookingTypeEnum $type): void
    {
        $builder->where($this->getTable() . '.type', $type);
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
