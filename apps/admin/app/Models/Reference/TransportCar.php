<?php

namespace App\Admin\Models\Reference;

use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class TransportCar extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'mark%', 'model%'];

    protected $table = 'r_transport_cars';

    protected $fillable = [
        'type_id',
        'mark',
        'model',
        'passengers_number',
        'bags_number',
        'image_guid'
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('r_transport_cars.*')
                ->join('r_transport_types', 'r_transport_types.id', '=', 'r_transport_cars.type_id')
                ->joinTranslatable('r_transport_types', 'name as type_name');
        });
    }

    public function __toString()
    {
        return $this->mark . ' ' . $this->model;
    }
}
