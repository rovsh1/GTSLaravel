<?php

namespace App\Admin\Models\Supplier;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Service extends \Module\Supplier\Infrastructure\Models\Service
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'supplier_services.title%'];

    protected $casts = [
        'data' => 'array',
        'supplier_id' => 'int',
        'type' => ServiceTypeEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->orderBy('name')
                ->addSelect('supplier_services.*')
                ->join('suppliers', 'suppliers.id', '=', 'supplier_services.supplier_id')
                ->addSelect('suppliers.name as provider_name');
        });
    }

    public function scopeWhereCity(Builder $builder, int $cityId): void
    {
        $builder->whereExists(function (QueryBuilder $query) use ($cityId) {
            $query->select(DB::raw(1))
                ->from('supplier_cities as t')
                ->whereColumn('t.supplier_id', 'supplier_services.supplier_id')
                ->where('city_id', $cityId);
        });
    }

    /**
     * @return array<int, ServiceSettingsField>
     */
    public function getSettingsFields(): array
    {
        return match ($this->type) {
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY => $this->buildRailwaySettings(),
            ServiceTypeEnum::TRANSFER_TO_RAILWAY => $this->buildRailwaySettings(),
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->buildAirportSettings(),
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->buildAirportSettings(),
            ServiceTypeEnum::INTERCITY_TRANSFER => $this->buildIntercityTransferSettings(),
            ServiceTypeEnum::DAY_CAR_TRIP => $this->buildDayCarTripSettings(),
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->buildCIPRoomInAirportSettings(),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER => $this->buildCarRentWithDriverSettings(),
            ServiceTypeEnum::OTHER_SERVICE => [],
            default => throw new \RuntimeException('Unknown service type')
        };
    }

    private function buildRailwaySettings(): array
    {
        return [
            ServiceSettingsField::createSelect('cityId', $this->data['cityId'] ?? null),
            ServiceSettingsField::createSelect('railwayStationId', $this->data['railwayStationId'] ?? null),
        ];
    }

    private function buildAirportSettings(): array
    {
        return [
            ServiceSettingsField::createSelect('airportId', $this->data['airportId'] ?? null),
        ];
    }

    private function buildIntercityTransferSettings(): array
    {
        return [
            ServiceSettingsField::createSelect('fromCityId', $this->data['fromCityId'] ?? null),
            ServiceSettingsField::createSelect('toCityId', $this->data['toCityId'] ?? null),
            ServiceSettingsField::createBool('returnTripIncluded', $this->data['returnTripIncluded'] ?? null),
        ];
    }

    private function buildDayCarTripSettings(): array
    {
        return [
            ServiceSettingsField::createSelect('cityId', $this->data['cityId'] ?? null),
        ];
    }

    private function buildCarRentWithDriverSettings(): array
    {
        return [
            ServiceSettingsField::createSelect('cityId', $this->data['cityId'] ?? null),
        ];
    }

    private function buildCIPRoomInAirportSettings(): array
    {
        return [
            ServiceSettingsField::createSelect('airportId', $this->data['airportId'] ?? null),
        ];
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
