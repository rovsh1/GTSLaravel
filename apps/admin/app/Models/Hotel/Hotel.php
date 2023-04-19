<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Enums\Hotel\VisibilityEnum;
use App\Admin\Enums\Hotel\StatusEnum;
use App\Admin\Models\Hotel\Reference\Service;
use App\Admin\Models\Hotel\Reference\Usability;
use App\Admin\Models\Reference\Landmark;
use App\Admin\Support\Models\HasCoordinates;
use Carbon\CarbonPeriod;
use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Module\HotelOld\Infrastructure\Models\Room;

/**
 * @property int city_id
 * @property int type_id
 * @property string name
 * @property int rating
 * @property string address
 * @property-read Collection<Room>|Room[] $rooms
 * @property-read Collection<Season>|Season[] $seasons
 * @property-read Collection<Contact>|Contact[] $contacts
 * @property-read Collection<Service>|Service[] $services
 * @property-read Collection<Usability>|Usability[] $usabilities
 * @property-read Collection<Landmark>|Landmark[] $landmarks
 * @method static Builder|Hotel withActiveContract()
 * @method static Builder|Hotel wherePeriod(CarbonPeriod $period)
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
        'visibility',
    ];

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
        'rating' => 'int',
        'status' => StatusEnum::class,
        'visibility' => VisibilityEnum::class
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

    public function landmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            Landmark::class,
            'hotel_landmark',
            'hotel_id',
            'landmark_id',
            'id',
            'id',
        )
            ->addSelect('hotel_landmark.distance');
    }

    public function scopeWithActiveContract(Builder $builder)
    {
        $builder->addSelect("{$this->getTable()}.*");

        $joinableTable = with(new Contract)->getTable();
        $builder->leftJoin(
            $joinableTable,
            function (JoinClause $join) use ($joinableTable) {
                $join->on("{$joinableTable}.hotel_id", '=', "{$this->getTable()}.id")
                    ->where("{$joinableTable}.status", Contract\StatusEnum::ACTIVE);
            }
        )->addSelect("{$joinableTable}.date_start as contract_date_start")
            ->addSelect("{$joinableTable}.date_end as contract_date_end");
    }

    public function scopeWherePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->where("hotel_contracts.date_start", '<=', $period->getStartDate())
            ->where('hotel_contracts.date_end', '>=', $period->getEndDate());
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'hotel_id', 'id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'hotel_services',
            'hotel_id',
            'service_id',
            'id',
            'id',
        )
            ->addSelect('hotel_services.is_paid')
            ->addSelect('hotel_services.service_id');
    }

    public function usabilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Usability::class,
            'hotel_usabilities',
            'hotel_id',
            'usability_id',
            'id',
            'id',
        )
            ->addSelect('hotel_usabilities.usability_id')
            ->addSelect('hotel_usabilities.room_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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
