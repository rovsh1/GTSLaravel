<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Models;

use App\Admin\Support\View\Form\ValueObject\NumRangeValue;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Infrastructure\Models\Booking as BaseModel;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

/**
 * Module\Booking\Airport\Infrastructure\Models\Booking
 *
 * @property int $id
 * @property-read int[] $guest_ids
 */
class Booking extends BaseModel
{
    use HasQuicksearch, SoftDeletes;

    protected $attributes = [
        'type' => BookingTypeEnum::AIRPORT,
    ];

    protected array $quicksearch = ['id'];

    private bool $isDetailsJoined = false;

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

    public function scopeWhereCityId(Builder $builder, int $id): void
    {
        $builder->withDetails()->where('r_airports.city_id', $id);
    }

    public function scopeWhereServiceId(Builder $builder, int $id): void
    {
        $builder->withDetails()->where('booking_airport_details.service_id', $id);
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

    public function scopeWhereDatePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->withDetails()->whereBetween('booking_airport_details.date', [
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

    public function scopeWhereGuestsCount(Builder $builder, ?NumRangeValue $numRange): void
    {
        if ($numRange === null) {
            return;
        }
        $builder->whereExists(function (QueryBuilder $query) use ($numRange) {
            $query->select(\DB::raw(1))
                ->from('booking_airport_guests as r')
                ->whereColumn('r.booking_airport_id', '=', 'bookings.id')
                ->havingRaw("COUNT(`id` between {$numRange->from} and {$numRange->to})");
        });
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
