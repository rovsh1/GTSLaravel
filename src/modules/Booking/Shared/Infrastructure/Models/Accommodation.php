<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Infrastructure\Models\Concerns\HasGuestsTrait;
use Sdk\Module\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasGuestsTrait;

    protected $table = 'booking_hotel_accommodations';

    protected $fillable = [
        'booking_id',
        'hotel_room_id',
        'room_name',
        'data',

        'guestIds'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('booking_hotel_accommodations.*')
                ->join('bookings', 'bookings.id', '=', 'booking_hotel_accommodations.booking_id')
                ->addSelect('bookings.order_id as booking_order_id');
        });
        static::bootGuests();
    }

    public function scopeWhereBookingId(Builder $builder, int $id): void
    {
        $builder->where('booking_hotel_accommodations.booking_id', $id);
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_hotel_accommodations.id', $id);
    }

    protected function getGuestsTable(): string
    {
        return 'booking_hotel_room_guests';
    }

    protected function getForeignPivotKey(): string
    {
        return 'accommodation_id';
    }

    protected function getRelatedPivotKey(): string
    {
        return 'guest_id';
    }
}
