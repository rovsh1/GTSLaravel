<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Admin\Models\Hotel\Employee
 *
 * @property int $hotel_id
 * @property string $fullname
 * @property string $department
 * @property string $post
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereHotelId($value)
 */
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

    public function __toString()
    {
        return $this->fullname;
    }
}
