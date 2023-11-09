<?php

declare(strict_types=1);

namespace App\Admin\Models\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\SourceEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Booking extends \Module\Booking\Shared\Infrastructure\ServiceBooking\Models\Booking
{
    use HasQuicksearch;

    protected array $quicksearch = ['id'];

    public function scopeWhereCreatedPeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereBetween(
            'bookings.created_at',
            [$period->getStartDate()->clone()->startOfDay(), $period->getEndDate()->clone()->endOfDay()]
        );
    }

    public function scopeWhereStatus(Builder $builder, BookingStatusEnum|int $status): void
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
                ->from('booking_hotel_rooms')
                ->whereColumn('bookings.id', 'booking_hotel_rooms.booking_id')
                ->where('hotel_room_id', $roomId);
        });
    }

    public function scopeWhereSource(Builder $builder, string|SourceEnum $source): void
    {
        $builder->where('bookings.source', $source);
    }
}
