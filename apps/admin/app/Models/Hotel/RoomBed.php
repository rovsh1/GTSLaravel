<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;

class RoomBed extends Model
{
    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'hotel_room_beds';

    protected $fillable = [
        'room_id',
        'type_id',
        'beds_number',
        'beds_size',
    ];

    protected $casts = [
        'room_id' => 'int',
        'type_id' => 'int',
        'beds_number' => 'int',
    ];
}
