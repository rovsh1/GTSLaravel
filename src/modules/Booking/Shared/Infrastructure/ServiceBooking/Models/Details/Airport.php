<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\ServiceBooking\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Factory\DetailsModelInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Airport extends Model implements DetailsModelInterface
{
    protected $table = 'booking_airport_details';

    protected $fillable = [
        'booking_id',
        'service_id',
        'date',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'service_type' => ServiceTypeEnum::class,
        'date' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('booking_airport_details.*')
                ->join(
                    'supplier_services',
                    'supplier_services.id',
                    '=',
                    'booking_airport_details.service_id'
                )
                ->addSelect('supplier_services.type as service_type');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_airport_details.id', $id);
    }

    public function bookingId(): int
    {
        return $this->booking_id;
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->service_type;
    }
}
