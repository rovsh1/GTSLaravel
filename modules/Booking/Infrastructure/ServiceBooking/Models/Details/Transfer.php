<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsModelInterface;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Transfer extends Model implements DetailsModelInterface
{
    protected $table = 'booking_transfer_details';

    protected $fillable = [
        'booking_id',
        'service_id',
        'date_start',
        'date_end',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'service_type' => ServiceTypeEnum::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('booking_transfer_details.*')
                ->join('supplier_services', 'supplier_services.id', '=', 'booking_transfer_details.service_id')
                ->addSelect('supplier_services.type as service_type');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_transfer_details.id', $id);
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
