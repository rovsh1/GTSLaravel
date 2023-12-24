<?php

declare(strict_types=1);

namespace App\Hotel\Models;

use App\Admin\Support\View\Form\ValueObject\NumRangeValue;
use App\Hotel\Services\HotelService;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Booking\Enum\QuotaProcessingMethodEnum;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Shared\Enum\SourceEnum;

class Booking extends \Module\Booking\Shared\Infrastructure\Models\Booking
{
    use HasQuicksearch;

    protected array $quicksearch = ['id'];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->whereExists(function (Query $query) {
                $query->selectRaw(1)
                    ->from('booking_hotel_details')
                    ->whereColumn('booking_hotel_details.booking_id', 'bookings.id')
                    ->where('booking_hotel_details.hotel_id', app(HotelService::class)->getHotelId());
            });
        });
    }

    public function scopeWhereByRequest(Builder $builder): void
    {
        $builder->whereExists(function (Query $query) {
            $query->selectRaw(1)
                ->from('booking_hotel_details')
                ->whereColumn('booking_hotel_details.booking_id', 'bookings.id')
                ->where('booking_hotel_details.quota_processing_method', QuotaProcessingMethodEnum::REQUEST);
        });
    }

    public function scopeWhereByQuota(Builder $builder): void
    {
        $builder->whereExists(function (Query $query) {
            $query->selectRaw(1)
                ->from('booking_hotel_details')
                ->whereColumn('booking_hotel_details.booking_id', 'bookings.id')
                ->where('booking_hotel_details.quota_processing_method', QuotaProcessingMethodEnum::QUOTA);
        });
    }

    public function scopeWhereCreatedPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween(
            'bookings.created_at',
            [$period->getStartDate()->clone()->startOfDay(), $period->getEndDate()->clone()->endOfDay()]
        );
    }

    public function scopeWhereStatus(Builder $builder, StatusEnum|int $status): void
    {
        $builder->where('bookings.status', $status);
    }

    public function scopeWhereManagerId(Builder $builder, int $managerId): void
    {
        $builder->where('administrator_bookings.administrator_id', $managerId);
    }

    public function scopeWithoutHotelBooking(Builder $builder): void
    {
        $builder->whereNotExists(function (Query $query) {
            $query->selectRaw(1)
                ->from('booking_hotel_details')
                ->whereColumn($this->table . '.id', '=', 'booking_hotel_details.booking_id');
        });
    }

    public function scopeWhereCityId(Builder $builder, int $cityId): void
    {
        $builder->where('hotels.city_id', $cityId);
    }

    public function scopeWhereHotelRoomId(Builder $builder, int $roomId): void
    {
        $builder->whereExists(function (Query $query) use ($roomId) {
            $query->selectRaw(1)
                ->from('booking_hotel_accommodations')
                ->whereColumn('bookings.id', 'booking_hotel_accommodations.booking_id')
                ->where('hotel_room_id', $roomId);
        });
    }

    public function scopeWhereSource(Builder $builder, string|SourceEnum $source): void
    {
        $builder->where('bookings.source', $source);
    }

    public function scopeWhereGuestsCount(Builder $builder, NumRangeValue $range): void
    {
        if ($range->from !== null) {
            $builder->having('guests_count', '>=', $range->from);
        }
        if ($range->to !== null) {
            $builder->having('guests_count', '<=', $range->to);
        }
    }

    public function scopeWhereStartPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween(
            'booking_hotel_details.date_start', [
                $period->getStartDate()->startOfDay(),
                $period->getEndDate()->endOfDay()
            ]
        );
    }

    public function scopeWhereEndPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween(
            'booking_hotel_details.date_end', [
                $period->getStartDate()->startOfDay(),
                $period->getEndDate()->endOfDay()
            ]
        );
    }
}
