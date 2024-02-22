<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Models\Details;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Module\Booking\Shared\Infrastructure\Models\Guest;
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

        'guestIds'
    ];

    protected $casts = [
        'data' => 'array',
        'service_type' => ServiceTypeEnum::class,
        'date' => 'datetime',
    ];

    private array $savingGuestIds;

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

        static::saved(function (self $model) {
            if (isset($model->savingGuestIds)) {
                $model->guests()->sync($model->savingGuestIds);
                unset($model->savingGuestIds);
            }
        });
    }

    public function guestIds(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->guests()->pluck('guest_id')->toArray(),
            set: function (array $guestIds) {
                $this->savingGuestIds = $guestIds;

                return [];
            }
        );
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(
            Guest::class,
            'booking_airport_guests',
            'booking_id',
            'guest_id',
            'booking_id'
        );
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('booking_airport_details.id', $id);
    }

    public function serviceType(): ServiceTypeEnum
    {
        return $this->service_type;
    }
}
