<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Models;

use Sdk\Module\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'hotel_contacts';

    protected $casts = [
        'is_main' => 'boolean'
    ];
}
