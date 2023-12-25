<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Infrastructure\Model;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int $booking_id
 * @property EventGroupEnum $group
 * @property string $field
 * @property string $description
 * @property mixed $before
 * @property mixed $after
 * @property array $context
 * @property \DateTimeInterface $created_at
 */
class BookingHistory extends Model
{
    public $timestamps = false;

    protected $table = 'booking_history';

    protected $fillable = [
        'booking_id',
        'group',
        'field',
        'description',
        'before',
        'after',
        'context',
        'created_at'
    ];

    protected $casts = [
        'booking_id' => 'int',
        'group' => EventGroupEnum::class,
        'context' => 'array',
        'created_at' => 'datetime',
    ];

    public function scopeWhereGroup(Builder $builder, EventGroupEnum $group): void
    {
        $builder->where('group', $group->name);
    }
}
