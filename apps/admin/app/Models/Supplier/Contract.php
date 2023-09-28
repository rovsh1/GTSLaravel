<?php

namespace App\Admin\Models\Supplier;

use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Support\Models\HasPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Contract extends Model
{
    use HasQuicksearch, HasPeriod;

    protected array $quicksearch = ['id'];

    protected $table = 'supplier_contracts';

    protected $fillable = [
        'supplier_id',
        'date_start',
        'date_end',
        'status',
        'service_type',
        'service_id',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class,
        'service_type' => ContractServiceTypeEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('supplier_contracts.*');
            $builder->selectSub(
                DB::table('hotels')
                    ->select('name')
                    ->whereColumn('hotels.id', 'supplier_contracts.service_id')
                    ->where('supplier_contracts.service_type', ContractServiceTypeEnum::HOTEL),
                'hotel_name',
            );
            $builder->selectSub(
                DB::table('supplier_airport_services')
                    ->select('name')
                    ->whereColumn('supplier_airport_services.id', 'supplier_contracts.service_id')
                    ->whereColumn('supplier_airport_services.supplier_id', 'supplier_contracts.supplier_id')
                    ->where('supplier_contracts.service_type', ContractServiceTypeEnum::AIRPORT),
                'airport_service_name',
            );
            $builder->selectSub(
                DB::table('supplier_transfer_services')
                    ->select('name')
                    ->whereColumn('supplier_transfer_services.id', 'supplier_contracts.service_id')
                    ->whereColumn('supplier_transfer_services.supplier_id', 'supplier_contracts.supplier_id')
                    ->where('supplier_contracts.service_type', ContractServiceTypeEnum::TRANSFER),
                'transfer_service_name',
            );
        });
        static::saved(function (self $model): void {
            if ($model->isActive()) {
                static::where('id', '!=', $model->id)
                    ->whereServiceType($model->service_type)
                    ->whereServiceId($model->service_id)
                    ->whereStatus(StatusEnum::ACTIVE)
                    ->update(['status' => StatusEnum::INACTIVE]);
            }
        });
    }

    public function serviceName(): Attribute
    {
        return Attribute::get(fn() => match ($this->service_type) {
            ContractServiceTypeEnum::HOTEL => $this->hotel_name,
            ContractServiceTypeEnum::AIRPORT => $this->airport_service_name,
            ContractServiceTypeEnum::TRANSFER => $this->transfer_service_name,
            default => null
        });
    }

    public function isActive(): bool
    {
        return $this->status === StatusEnum::ACTIVE;
    }

    public function __toString()
    {
        return 'Договор №' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
