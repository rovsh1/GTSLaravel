<?php

namespace App\Admin\Models\Reference;

use Custom\Framework\Database\Eloquent\Model;

class LandmarkType extends Model
{
    public $timestamps = false;

    protected $table = 'ref_landmark_types';

    protected $fillable = [
        'alias',
        'name',
        'in_city',
        //'system_status',
    ];

    protected $casts = [
        'in_city' => 'bool',
    ];

    public function __toString()
    {
        return (string)$this->name;
    }
}
