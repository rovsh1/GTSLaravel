<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

/**
 * Module\Booking\Infrastructure\Shared\Models\Booking
 *
 * @property int $id
 * @property int $order_id
 * @property int $service_id
 * @property ServiceTypeEnum $service_type
 * @property BookingStatusEnum $status
 * @property string $source
 * @property int $creator_id
 * @property array $price
 * @property array $cancel_conditions
 * @property string|null $note
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
abstract class Booking extends Model
{
    use HasQuicksearch, SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'order_id',
        'service_type',
        'status',
        'source',
        'creator_id',
        'price',
        'cancel_conditions',
        'note',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
        'service_type' => ServiceTypeEnum::class,
        'price' => 'array',
        'cancel_conditions' => 'array',
    ];

    public function scopeApplyCriteria(Builder $query, array $criteria): void
    {
        if (isset($criteria['quicksearch'])) {
            $query->quicksearch($criteria['quicksearch']);
            unset($criteria['quicksearch']);
        }

        foreach ($criteria as $k => $v) {
            $scopeName = \Str::camel($k);
            $scopeMethod = 'where' . ucfirst($scopeName);
            $hasScope = $this->hasNamedScope($scopeMethod);
            if ($hasScope) {
                $query->$scopeMethod($v);
                continue;
            }
            $query->where($k, $v);
        }
    }
}
