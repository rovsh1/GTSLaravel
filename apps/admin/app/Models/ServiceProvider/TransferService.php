<?php

namespace App\Admin\Models\ServiceProvider;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\Booking\TransferServiceTypeEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class TransferService extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'service_provider_transfer_services.name%'];

    protected $table = 'service_provider_transfer_services';

    protected $fillable = [
        'provider_id',
        'name',
        'type',
    ];

    protected $casts = [
        'provider_id' => 'int',
        'type' => TransferServiceTypeEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->orderBy('name')
                ->addSelect('service_provider_transfer_services.*')
                ->join('service_providers', 'service_providers.id', '=', 'service_provider_transfer_services.provider_id')
                ->addSelect('service_providers.name as provider_name');
        });
    }

    public function scopeWhereCity(Builder $builder, int $cityId): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($cityId) {
            $query->select(DB::raw(1))
                ->from('service_provider_cities as t')
                ->whereColumn('t.provider_id', 'service_provider_transfer_services.provider_id')
                ->where('city_id', $cityId);
        });
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
