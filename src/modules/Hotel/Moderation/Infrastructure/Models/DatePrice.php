<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shared\Models\Model;

class DatePrice extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_season_price_calendar';

    protected $fillable = [
        'date',
        'season_id',
        'group_id',
        'room_id',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'float'
    ];

    public function scopeWithGroup(Builder $builder): void
    {
        $builder
            ->addSelect('hotel_season_price_calendar.*')
            ->join('hotel_price_groups', 'hotel_price_groups.id', '=', 'hotel_season_price_calendar.group_id')
            ->addSelect('hotel_price_groups.rate_id as rate_id')
            ->addSelect('hotel_price_groups.guests_count as guests_count')
            ->addSelect('hotel_price_groups.is_resident as is_resident');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }
}
