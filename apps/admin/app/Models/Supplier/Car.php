<?php

namespace App\Admin\Models\Supplier;

use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\TransportCar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Car extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'mark%', 'model%'];

    protected $table = 'supplier_cars';

    public $timestamps = false;

    protected $fillable = [
        'supplier_id',
        'car_id',
    ];

    private array $savingCities;

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('supplier_cars.*')
                ->join('r_transport_cars', 'r_transport_cars.id', '=', 'supplier_cars.car_id')
                ->addSelect('r_transport_cars.mark as mark')
                ->addSelect('r_transport_cars.model as model');
        });
    }

    public function transportCar(): BelongsTo
    {
        return $this->belongsTo(TransportCar::class);
    }

    public function __toString()
    {
        return $this->mark . ' ' . $this->model;
    }
}
