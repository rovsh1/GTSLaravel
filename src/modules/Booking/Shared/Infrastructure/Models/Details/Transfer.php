<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Booking\Shared\Infrastructure\Models\CarBid;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ServiceTypeEnum;

class Transfer extends Model
{
    protected $table = 'booking_transfer_details';

    protected $fillable = [
        'booking_id',
        'service_id',
        'date_start',
        'date_end',
        'data',

        'carBids',
    ];

    protected $casts = [
        'data' => 'array',
        'service_type' => ServiceTypeEnum::class,
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('booking_transfer_details.*')
                ->join('supplier_services', 'supplier_services.id', '=', 'booking_transfer_details.service_id')
                ->addSelect('supplier_services.type as service_type');
        });
    }

    public function carBids(): HasMany
    {
        return $this->hasMany(
            CarBid::class,
            'booking_id',
            'booking_id'
        );
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_transfer_details.id', $id);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->service_type;
    }
}
