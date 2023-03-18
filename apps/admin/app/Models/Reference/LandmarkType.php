<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LandmarkType extends Model
{
    public $timestamps = false;

    protected $table = 'r_landmark_types';

    protected $fillable = [
        'alias',
        'name',
        'in_city',
        //'system_status',
    ];

    protected $casts = [
        'in_city' => 'bool',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->orderBy('r_landmark_types.name', 'asc');
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
