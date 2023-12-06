<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Infrastructure\Enum\StatusSettingsEntityEnum;
use Sdk\Module\Database\Eloquent\Model;

class StatusSettings extends Model
{
    protected $table = 'booking_status_settings';

    protected $fillable = [
        'entity_type',
        'status',
        'name_ru',
        'name_en',
        'name_uz',
        'color',
    ];

    protected $casts = [
        'entity_type' => StatusSettingsEntityEnum::class,
        'status' => 'int'
    ];

    public function scopeOnlyOrderStatuses(Builder $builder): void
    {
        $builder->where('entity_type', StatusSettingsEntityEnum::ORDER->value);
    }

    public function scopeOnlyBookingStatuses(Builder $builder): void
    {
        $builder->where('entity_type', StatusSettingsEntityEnum::BOOKING->value);
    }
}
