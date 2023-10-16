<?php

namespace App\Admin\Models\Supplier;

use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Support\Models\HasPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\ServiceTypeEnum;
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
        'service_id',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class,
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('supplier_contracts.*')
                ->join('supplier_services', 'supplier_services.id', '=', 'supplier_contracts.service_id');
            $builder->selectSub(
                DB::table('hotels')
                    ->select('name')
                    ->whereColumn('hotels.id', 'supplier_contracts.service_id')
                    ->where('supplier_services.type', ServiceTypeEnum::HOTEL_BOOKING),
                'hotel_name',
            );
            $builder->selectSub(
                DB::table('supplier_services')
                    ->select('title')
                    ->whereColumn('supplier_services.id', 'supplier_contracts.service_id')
                    ->whereColumn('supplier_services.supplier_id', 'supplier_contracts.supplier_id')
                    ->where('supplier_services.type', '!=', ServiceTypeEnum::HOTEL_BOOKING),
                'service_name',
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
        return Attribute::get(fn() => $this->service_name ?? $this->hotel_name);
    }

    public function scopeWhereSupplierId(Builder $builder, int $id): void
    {
        $builder->where('supplier_contracts.supplier_id', $id);
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
