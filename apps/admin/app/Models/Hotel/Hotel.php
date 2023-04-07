<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Models\HasCoordinates;
use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int city_id
 * @property int type_id
 * @property string name
 * @property int rating
 * @property string address
 * @property HasMany rooms
 * @property HasMany seasons
 */
class Hotel extends Model
{
    use HasQuicksearch;
    use HasCoordinates;

    //use SoftDeletes;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'hotels';

    protected $fillable = [
        'city_id',
        'type_id',
        'name',
        'rating',
        'address',
//->addAttribute('citycenter_distance','number', ['default' => 0, 'nonnegative' => true, 'allowZero' => true]),
        'address_lat',
        'address_lon',
        'zipcode',
//    'text',
        'status',
        'city_distance',
//    'visible_for',
    ];

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
        'rating' => 'int',
        'status' => 'int',
    ];

    public static function saving($callback) {}

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotels.*')
                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->join('r_enums', 'r_enums.id', '=', 'hotels.type_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->joinTranslatable('r_enums', 'name as type_name');
        });
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'hotel_id', 'id');
    }

    public function updateRoomsPositions($ids): bool
    {
        $i = 1;
        foreach ($ids as $id) {
            $room = Room::find($id);
            if (!$room) {
                throw new \Exception('Room not found', 404);
            }

            $room->update(['position' => $i++]);
        }
        return true;
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    protected function getLatitudeField(): string
    {
        return 'address_lat';
    }

    protected function getLongitudeField(): string
    {
        return 'address_lon';
    }
}
