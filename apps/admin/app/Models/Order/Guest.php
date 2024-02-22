<?php

declare(strict_types=1);

namespace App\Admin\Models\Order;

use Illuminate\Database\Eloquent\Builder;

class Guest extends \Module\Booking\Shared\Infrastructure\Models\Guest
{
    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->join('r_countries', 'r_countries.id', 'order_guests.country_id')
                ->addSelect('order_guests.*')
                ->joinTranslatable('r_countries', 'name as country_name');
        });
    }
}
