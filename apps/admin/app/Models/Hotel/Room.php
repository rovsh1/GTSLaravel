<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property HasMany beds
 */
class Room extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_rooms';

    protected $fillable = [
        'hotel_id',
        'name_id',
        'type_id',
        'custom_name',
        'rooms_number',
        'guests_number',
        'square',
        'position',
    ];

    protected $casts = [
        'hotel_id' => 'int',
        'name_id' => 'int',
        'type_id' => 'int',
        'rooms_number' => 'int',
        'guests_number' => 'int',
        'square' => 'int',
        'position' => 'int',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_rooms.*')
                //->join('clients', 'clients.id', '=', 'price_lists.client_id')
                ->join('r_enums as r_names', 'r_names.id', '=', 'hotel_rooms.name_id')
                ->join('r_enums as r_types', 'r_types.id', '=', 'hotel_rooms.type_id')
                ->joinTranslatable('r_names', 'name')
                ->joinTranslatable('r_types', 'name as type_name')
                ->orderBy('position', 'asc');
        });
    }

    public function beds(): HasMany
    {
        return $this->hasMany(RoomBed::class);
    }

    public function updateBeds(array $beds)
    {
        RoomBed::where('room_id', $this->id)->delete();
        foreach ($beds as $bed) {
            RoomBed::create(array_merge($bed, ['room_id' => $this->id]));
        }
    }

    public function __toString()
    {
        return $this->name . ($this->custom_name ? ' (' . $this->custom_name . ')' : '');
    }
}
