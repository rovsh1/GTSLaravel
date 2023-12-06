<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sdk\Module\Database\Eloquent\Model;

class Accommodation extends Model
{
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

    private array $guestIds;

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('booking_hotel_accommodations.*')
                ->join('bookings', 'bookings.id', '=', 'booking_hotel_accommodations.booking_id')
                ->addSelect('bookings.order_id as booking_order_id');
        });

        static::saved(function (self $model) {
            if (isset($model->guestIds)) {
                $model->guests()->sync($model->guestIds);
                unset($model->guestIds);
            }
        });
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
        return Attribute::make(
            get: fn() => $this->guests()->pluck('guest_id')->toArray(),
            set: function (array $guestIds) {
                $this->guestIds = $guestIds;

                return [];
            }
        );
    }
}
