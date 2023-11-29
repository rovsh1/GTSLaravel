<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ServiceTypeEnum;

class Airport extends Model
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

    //@todo Дубль хранения?
//DB::table('booking_airport_guests')->updateOrInsert(
//['booking_airport_id' => $bookingId->value(), 'guest_id' => $guestId->value()],
//['booking_airport_id' => $bookingId->value(), 'guest_id' => $guestId->value()]
//);

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_airport_details.id', $id);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->service_type;
    }
}
