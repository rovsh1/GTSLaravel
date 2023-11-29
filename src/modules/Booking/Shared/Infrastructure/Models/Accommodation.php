<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $table = 'booking_hotel_accommodations';

    protected $fillable = [
        'booking_id',
        'hotel_room_id',
        'room_name',
        'data',
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
    }

    //@todo replace to setter
    public static function setGuests(int $accommodationId, array $guestsIds): void
    {
        $guestsInsert = [];
        foreach ($guestsIds as $guestId) {
            $guestsInsert[] = ['accommodation_id' => $accommodationId, 'guest_id' => $guestId];
        }
        DB::table('booking_hotel_room_guests')
            ->where('accommodation_id', $accommodationId)
            ->delete();
        DB::table('booking_hotel_room_guests')->insert($guestsInsert);
    }

    public function scopeWhereBookingId(Builder $builder, int $id): void
    {
        $builder->where('booking_hotel_accommodations.booking_id', $id);
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_hotel_accommodations.id', $id);
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(
            Guest::class,
            'booking_hotel_room_guests',
            'accommodation_id',
            'guest_id'
        );
    }

    public function guestIds(): Attribute
    {
        return Attribute::get(
            fn() => $this->guests()->pluck('guest_id')->toArray()
        );
    }
}
