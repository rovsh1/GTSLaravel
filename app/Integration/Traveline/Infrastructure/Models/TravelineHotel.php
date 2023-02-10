<?php

namespace GTS\Integration\Traveline\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;

class TravelineHotel extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'traveline_hotels';

    protected $fillable = [
        'hotel_id'
    ];

}
