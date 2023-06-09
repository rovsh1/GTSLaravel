<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class StatusSettings extends Model
{
    protected $table = 'booking_status_settings';

    protected $fillable = [
        'value',
        'name_ru',
        'name_en',
        'name_uz',
        'color',
    ];
}
