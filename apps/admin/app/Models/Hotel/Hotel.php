<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Enums\Hotel\RatingEnum;
use App\Admin\Enums\Hotel\StatusEnum;
use App\Admin\Enums\Hotel\VisibilityEnum;
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
 * @property-read Collection<Image>|Image[] $images
 * @method static Builder|Hotel wherePeriod(CarbonPeriod $period)
 * @method static Builder|Hotel withRoomsCount()
 * @mixin \Eloquent
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
        'rating' => RatingEnum::class,
        'status' => StatusEnum::class,
        'visibility' => VisibilityEnum::class
    ];

    public static function saving($callback)
    {
    }

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

    public function scopeWherePeriod(Builder $builder, CarbonPeriod $period): void
    {
        $builder->whereHas('contracts', function (Builder $query) use ($period) {
            $query
                ->where('date_start', '=', $period->getStartDate())
                ->where('date_end', '=', $period->getEndDate());
        });
    }

    public function scopeWithRoomsCount(Builder $builder): void
    {
        $builder->addSelect(
            \DB::raw("(SELECT COUNT(`id`) FROM `hotel_rooms` WHERE `hotel_id`=`hotels`.`id`) as rooms_count")
        );
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

    protected function getLatitudeField(): string
    {
        return 'address_lat';
    }

    protected function getLongitudeField(): string
    {
        return 'address_lon';
    }

    /**
     * @param array $ids
     * @param class-string $model
     * @param string $indexField
     * @return bool
     * @throws \Throwable
     */
    private function updateChildIndexes(array $ids, string $model, string $indexField = 'index'): bool
    {
        $i = 1;
        foreach ($ids as $id) {
            $image = $model::find($id);
            if (!$image) {
                throw new \Exception('Model not found', 404);
            }

            $image->update([$indexField => $i++]);
        }
        return true;
    }
}
