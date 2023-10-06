<?php

declare(strict_types=1);

namespace App\Admin\Models\Pricing;

use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class MarkupGroup extends Model
{
    protected $table = 'client_markup_groups';

    protected $fillable = [
        'id',
        'name',
        'value',
        'type'
    ];

    protected $casts = [
        'type' => MarkupValueTypeEnum::class
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
//            $builder
//                ->addSelect('booking_hotel_rooms.*')
//                ->join('bookings', 'bookings.id', '=', 'booking_hotel_rooms.booking_id')
//                ->addSelect('bookings.order_id as booking_order_id');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}