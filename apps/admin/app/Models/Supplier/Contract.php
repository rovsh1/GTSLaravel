<?php

namespace App\Admin\Models\Supplier;

use App\Admin\Enums\Hotel\Contract\StatusEnum;
use App\Admin\Support\Models\HasPeriod;
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
    ];

    protected $casts = [
        'supplier_id' => 'int',
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class
    ];

    public static function booted()
    {
        static::saved(function (self $model): void {
            if ($model->isActive()) {
                static::where('id', '!=', $model->id)
                    ->whereStatus(StatusEnum::ACTIVE)
                    ->update(['status' => StatusEnum::INACTIVE]);
            }
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
