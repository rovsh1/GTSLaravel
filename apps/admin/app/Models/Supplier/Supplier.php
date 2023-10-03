<?php

namespace App\Admin\Models\Supplier;

use App\Admin\Models\Reference\Airport;
use App\Admin\Models\Reference\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', '%suppliers.name%'];

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'currency_id',

        'cities',
    ];

    private array $savingCities;

    public static function booted()
    {
        static::saved(function ($model) {
            if (isset($model->savingCities)) {
                $model->cities()->sync($model->savingCities);
                unset($model->savingCities);
            }
        });
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->orderBy('name')
                ->addSelect('suppliers.*')
                ->leftJoin('r_currencies', 'r_currencies.id', '=', 'suppliers.currency_id')
                ->joinTranslatable('r_currencies', 'name as currency_name');
        });
    }

    public function getRouteKey()
    {
        return parent::getRouteKey(); // TODO: Change the autogenerated stub
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'supplier_cities', 'supplier_id', 'city_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function airports(): BelongsToMany
    {
        return $this->belongsToMany(
            Airport::class,
            'supplier_airports',
            'supplier_id',
            'airport_id'
        );
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function transferServices(): HasMany
    {
        return $this->hasMany(TransferService::class);
    }

    public function airportServices(): HasMany
    {
        return $this->hasMany(AirportService::class);
    }

    public function getCitiesAttribute(): array
    {
        return $this->cities()->pluck('id')->toArray();
    }

    public function setCitiesAttribute(array $cities): void
    {
        $this->savingCities = $cities;
    }

    public function getForeignKey()
    {
        return 'supplier_id';
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
