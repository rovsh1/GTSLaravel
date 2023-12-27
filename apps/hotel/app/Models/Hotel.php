<?php

namespace App\Hotel\Models;

use App\Hotel\Models\Reference\Service;
use App\Hotel\Models\Reference\Usability;
use App\Hotel\Models\Reference\Landmark;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Hotel\RatingEnum;
use Sdk\Shared\Enum\Hotel\StatusEnum;
use Sdk\Shared\Enum\Hotel\VisibilityEnum;

/**
 * @property int city_id
 * @property int type_id
 * @property string name
 * @property int rating
 * @property string address
 * @property-read Collection<int, Room> $rooms
 * @property-read Collection<int, Contact> $contacts
 * @property-read Collection<int, Service> $services
 * @property-read Collection<int, Usability> $usabilities
 * @property-read Collection<int, Landmark> $landmarks
 * @property-read Collection<int, Image> $images
 * @method static Builder|Hotel wherePeriod(CarbonPeriod $period)
 * @method static Builder|Hotel withRoomsCount()
 * @mixin \Eloquent
 */
class Hotel extends Model
{
    use HasQuicksearch;
    use HasIndexedChildren;

    //use SoftDeletes;

    protected array $quicksearch = ['id', 'hotels.name%'];

    protected $table = 'hotels';

    protected $fillable = [
        'supplier_id',
        'city_id',
        'type_id',
        'currency',
        'name',
        'rating',
        'address',
        'address_en',
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
        'currency' => CurrencyEnum::class,
        'rating' => RatingEnum::class,
        'status' => StatusEnum::class,
        'visibility' => VisibilityEnum::class
    ];

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
        return $this->hasMany(Administrator::class);
    }

    protected function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * @param int[] $ids
     * @return bool
     * @throws \Throwable
     */
    public function updateRoomsPositions(array $ids): bool
    {
        return $this->updateChildIndexes($ids, Room::class, 'position');
    }

    /**
     * @param int[] $ids
     * @return bool
     * @throws \Throwable
     */
    public function updateImageIndexes(array $ids): bool
    {
        return $this->updateChildIndexes($ids, Image::class);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
