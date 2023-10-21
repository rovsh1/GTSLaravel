<?php

namespace App\Admin\Models\Supplier;

use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Support\Models\HasPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'service_type',

        'service_ids',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class,
        'service_type' => ServiceTypeEnum::class,
    ];

    private array $savingServiceIds;

    public static function booted()
    {
        static::saved(function (self $model) {
            if (isset($model->savingServiceIds)) {
                $model->services()->sync($model->savingServiceIds);
                unset($model->savingServiceIds);
            }
        });
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'supplier_service_contracts',
            'contract_id',
            'service_id',
        );
    }

    public function serviceIds(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->services()->pluck('id')->toArray(),
            set: function (array $ids) {
                $this->savingServiceIds = $ids;

                return [];
            }
        );
    }

    public function serviceNames(): Attribute
    {
        return Attribute::get(fn() => $this->services()->pluck('title')->toArray());
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
