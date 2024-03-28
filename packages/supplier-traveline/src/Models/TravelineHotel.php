<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Models;

use Sdk\Module\Database\Eloquent\Model;

class TravelineHotel extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'traveline_hotels';

    protected $fillable = [
        'hotel_id',
    ];
}
