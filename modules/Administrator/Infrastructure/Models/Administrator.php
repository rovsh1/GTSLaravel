<?php

namespace Module\Administrator\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sdk\Module\Database\Eloquent\Model;

class Administrator extends Model
{
    use HasFactory;

    protected $table = 'administrator_bookings';

    protected $fillable = [
        'booking_id',
        'administrator_id',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('administrator_bookings.*')
                ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
                ->addSelect([
                    'administrators.id',
                    'administrators.presentation',
                    'administrators.email',
                    'administrators.name',
                    'administrators.surname',
                    'administrators.phone',
                ]);
        });
    }
}
