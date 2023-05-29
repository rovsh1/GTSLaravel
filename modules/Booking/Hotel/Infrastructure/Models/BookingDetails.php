<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Models;

use Carbon\CarbonPeriod;
use Sdk\Module\Database\Eloquent\Model;

class BookingDetails extends Model
{
    protected $table = 'booking_hotel_details';

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'date_start',
        'date_end',
        'nights_count',
        'additional_data',//@todo Тип номера подтверждения бронирования
        'rooms',
    ];

    protected static function booted()
    {
        static::saving(function (self $model) {
            $model->nights_count = CarbonPeriod::create($model->date_start, $model->date_end, 'P1D')
                ->excludeEndDate()
                ->count();
        });
    }
}
