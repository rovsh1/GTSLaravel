<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Markup\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Booking\Infrastructure\HotelBooking\Models\Room\Guest;
use Module\Pricing\Domain\Markup\ValueObject\MarkupValueTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class ClientMarkupGroupRule extends Model
{

    //client_markup_groups
    protected $table = 'client_markup_group_rules';

    protected $fillable = [
        'group_id',
        'hotel_id',
        'hotel_room_id',
        'value',
        'type'
    ];

    protected $casts = [
        'type' => MarkupValueTypeEnum::class
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('booking_hotel_rooms.*')
                ->join('bookings', 'bookings.id', '=', 'booking_hotel_rooms.booking_id')
                ->addSelect('bookings.order_id as booking_order_id');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_hotel_rooms.id', $id);
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(
            Guest::class,
            'booking_hotel_room_guests',
            'booking_hotel_room_id',
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
