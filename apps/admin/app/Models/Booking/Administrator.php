<?php

declare(strict_types=1);

namespace App\Admin\Models\Booking;

use Custom\Framework\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table = 'administrator_bookings';

    protected $fillable = [
        'booking_id',
        'administrator_id'
    ];
}
