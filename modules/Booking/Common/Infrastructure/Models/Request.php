<?php

namespace Module\Booking\Common\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;

class Request extends Model
{
    use HasFactory;

    protected $table = 'booking_requests';

    protected $fillable = [
        'booking_id',
        'type',
        'file',
        'is_archive',
    ];

    protected $casts = [
        'type' => RequestTypeEnum::class,
        'is_archive' => 'boolean'
    ];
}
