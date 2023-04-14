<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'hotel_employees';

    protected $fillable = [
        'hotel_id',
        'fullname',
        'department',
        'post',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
