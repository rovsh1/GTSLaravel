<?php

namespace App\Admin\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Client extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'city_id',
        'currency_id',
        'type',
        'status',
        'description'
    ];

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('clients.*')
                ->join('r_cities', 'r_cities.id', '=', 'clients.city_id')
                ->joinTranslatable('r_cities', 'name as city_name')
                ->join('r_currencies', 'r_currencies.id', '=', 'clients.currency_id')
                ->joinTranslatable('r_currencies', 'name as currency_name');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('clients.id', $id);
    }

    public function legals(): HasMany
    {
        return $this->hasMany(Legal::class);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
