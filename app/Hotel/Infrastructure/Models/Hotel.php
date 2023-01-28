<?php

namespace GTS\Hotel\Infrastructure\Models;

use GTS\Shared\Infrastructure\Models\Model;

/**
 * GTS\Hotel\Infrastructure\Models\Hotel
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created
 * @property \Illuminate\Support\Carbon|null $updated
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Hotel extends Model
{
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';
    protected $table = 'hotels';
}
