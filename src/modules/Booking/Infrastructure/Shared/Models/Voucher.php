<?php

namespace Module\Booking\Infrastructure\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'order_vouchers';

    protected $fillable = [
        'order_id',
    ];
}
