<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Module\Booking\Shared\Infrastructure\Models\Concerns\HasGuestsTrait;
use Sdk\Module\Database\Eloquent\Model;

class CarBid extends Model
{
    use HasGuestsTrait;

    protected $table = 'booking_car_bids';

    protected $fillable = [
        'booking_id',
        'supplier_car_id',
        'cars_count',
        'passengers_count',
        'baggage_count',
        'baby_count',
        'prices',
    ];

    protected $casts = [
        'prices' => 'array'
    ];

    protected static function booted()
    {
        static::bootGuests();
    }

    protected function getGuestsTable(): string
    {
        return 'booking_car_bid_guests';
    }

    protected function getForeignPivotKey(): string
    {
        return 'car_bid_id';
    }

    protected function getRelatedPivotKey(): string
    {
        return 'guest_id';
    }
}
