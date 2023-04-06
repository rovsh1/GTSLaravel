<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'hotel_employees';

    protected $fillable = [
        'hotel_id',
        'fullname',
        'department',
        'post',
    ];
}
