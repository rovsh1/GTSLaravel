<?php

namespace Module\Booking\Common\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'order_vouchers';

    protected $fillable = [
        'order_id',
    ];
}
