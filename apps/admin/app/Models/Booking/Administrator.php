<?php

declare(strict_types=1);

namespace App\Admin\Models\Booking;

use Sdk\Module\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table = 'administrator_bookings';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'administrator_id'
    ];
}
