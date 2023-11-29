<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Infrastructure\Model;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Sdk\Module\Database\Eloquent\Model;

class BookingHistory extends Model
{
    public $timestamps = false;

    protected $table = 'booking_history';

    protected $fillable = [
        'booking_id',
        'group',
        'payload',
        'context',
        'created_at'
    ];

    protected $casts = [
        'booking_id' => 'int',
        'group' => EventGroupEnum::class,
        'payload' => 'array',
        'context' => 'array',
        'created_at' => 'datetime',
    ];

    public function scopeWhereGroup(Builder $builder, EventGroupEnum $group): void
    {
        $builder->where('group', $group->name);
    }
}
