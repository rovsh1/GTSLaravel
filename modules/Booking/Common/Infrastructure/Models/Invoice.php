<?php

namespace Module\Booking\Common\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'booking_invoices';

    protected $fillable = [
        'booking_id',
    ];
}
