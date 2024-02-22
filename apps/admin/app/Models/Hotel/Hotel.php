<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Models\HasIndexedChildren;
use App\Admin\Models\Hotel\Reference\Service;
use App\Admin\Models\Hotel\Reference\Usability;
use App\Admin\Models\Reference\Landmark;
use App\Admin\Support\Models\HasCoordinates;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\HasTranslations;
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
 * @property-read Collection<int, Season> $seasons
 * @property-read Collection<int, Contact> $contacts
 * @property-read Collection<int, Service> $services
 * @property-read Collection<int, Usability> $usabilities
 * @property-read Collection<int, Landmark> $landmarks
 * @property-read Collection<int, Image> $images
 * @property-read Collection<int, PriceRate> $priceRates
 * @method static Builder|Hotel wherePeriod(CarbonPeriod $period)
 * @method static Builder|Hotel withRoomsCount()
 * @mixin \Eloquent
 */
class Hotel extends Model
{
    use HasQuicksearch;
    use HasTranslations;
    use HasCoordinates;
    use HasIndexedChildren;

    //use SoftDeletes;

    protected array $quicksearch = ['id', 'hotels.name%'];

    protected $translatable = ['text'];

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
        'text',
        'status',
        'city_distance',
        'visibility',

        'is_traveline_integration_enabled',
    ];

    private bool $isNeedChangeTravelineIntegration = false;

    protected $casts = [
        'city_id' => 'int',
        'type_id' => 'int',
        'currency' => CurrencyEnum::class,
        'rating' => RatingEnum::class,
        'status' => StatusEnum::class,
        'visibility' => VisibilityEnum::class,
        'is_traveline_integration_enabled' => 'int',
    ];

    public static function saving($callback)
    {
    }

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotels.*')
                ->selectSub(function (Query $query) {
                    $query->selectRaw(1)
                        ->from('traveline_hotels')
                        ->whereColumn('traveline_hotels.hotel_id', 'hotels.id');
                }, 'is_traveline_integration_enabled')
                ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
                ->join('r_countries', 'r_countries.id', '=', 'r_cities.country_id')
                ->join('r_enums', 'r_enums.id', '=', 'hotels.type_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->joinTranslatable('r_countries', 'name as country_name')
                ->joinTranslatable('r_enums', 'name as type_name')
                ->joinTranslations();
        });

        static::saved(function (self $model) {
            if ($model->isNeedChangeTravelineIntegration) {
                $isIntegrationExists = DB::table('traveline_hotels')->where('hotel_id', $model->id)->exists();
                if ($isIntegrationExists) {
                    DB::table('traveline_hotels')->where('hotel_id', $model->id)->delete();
                } else {
                    DB::table('traveline_hotels')->insert(['hotel_id' => $model->id, 'created_at' => now()]);
                }
            }
        });
    }

    public function setIsTravelineIntegrationEnabledAttribute(int $value): void
    {
        $this->isNeedChangeTravelineIntegration = $value !== (int)$this->is_traveline_integration_enabled;
    }

    public function scopeWhereIsTravelineIntegrationEnabled(Builder $builder, int $isEnabled): void
    {
        $builder->having('is_traveline_integration_enabled', $isEnabled);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function seasons(): BelongsToMany
    {
        return $this->belongsToMany(
            Season::class,
            'hotel_contracts',
            'hotel_id',
            'id',
            'id',
            'contract_id',
        );
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
            $query->whereBetween('date_start', [$period->getStartDate(), $period->getEndDate()]);
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

    public function priceRates(): HasMany
    {
        return $this->hasMany(PriceRate::class);
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
}
