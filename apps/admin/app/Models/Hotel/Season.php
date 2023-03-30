<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 */
class Season extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_seasons';

    protected $fillable = [
        'hotel_id',
        'name',
    ];

    protected $casts = [
        'hotel_id' => 'int',
    ];

    public function __toString()
    {
        return (string)$this->name;
    }
}
