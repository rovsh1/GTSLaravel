<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\Booking\OrderStatusEnum;
use Sdk\Module\Database\Eloquent\Model;

class StatusSettings extends Model
{
    protected $table = 'booking_status_settings';

    protected $fillable = [
        'value',
        'type',
        'name_ru',
        'name_en',
        'name_uz',
        'color',
    ];

    public function scopeOnlyOrderStatuses(Builder $builder): void
    {
        $builder->where('type', OrderStatusEnum::class);
    }

    public function scopeOnlyBookingStatuses(Builder $builder): void
    {
        $builder->where('type', BookingStatusEnum::class);
    }
}
