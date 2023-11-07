<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Order\Models;

use Sdk\Module\Database\Eloquent\Model;

class StatusSettings extends Model
{
    protected $table = 'order_status_settings';

    protected $fillable = [
        'value',
        'name_ru',
        'name_en',
        'name_uz',
        'color',
    ];
}
