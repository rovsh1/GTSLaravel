<?php

declare(strict_types=1);

namespace App\Admin\Models\Supplier;

use App\Shared\Support\Facades\FileStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Http\UploadedFile;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Dto\UploadedFileDto;
use Sdk\Shared\Enum\PaymentStatusEnum;

class Payment extends \Module\Supplier\Payment\Infrastructure\Models\Payment
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'invoice_number'];

    protected $attributes = [
        'status' => PaymentStatusEnum::NOT_PAID,
    ];

    public function getFillable()
    {
        return [
            ...$this->fillable,
            'file',
        ];
    }

    protected static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->addSelect('supplier_payments.*')
                ->join('suppliers', 'suppliers.id', 'supplier_payments.supplier_id')
                ->addSelect('suppliers.name as supplier_name')
                ->selectSub(function (Query $query) {
                    $query->selectRaw('SUM(sum)')
                        ->from('supplier_payment_landings')
                        ->whereColumn('supplier_payment_landings.payment_id', 'supplier_payments.id');
                }, 'lend_sum')
                ->selectSub(function (Query $query) {
                    $query->selectRaw('supplier_payments.payment_sum - SUM(sum)')
                        ->from('supplier_payment_landings')
                        ->whereColumn('supplier_payment_landings.payment_id', 'supplier_payments.id');
                }, 'remaining_sum');
        });
    }

    public function file(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->document !== null ? FileStorage::find($this->document) : null,
            set: function (UploadedFile|FileDto|null $file) {
                if ($file === null) {
                    return [];
                }
                if ($file instanceof UploadedFile) {
                    $fileDto = UploadedFileDto::fromUploadedFile($file);
                    $file = FileStorage::create($fileDto->name, $fileDto->contents);
                }

                return [
                    'document' => $file->guid
                ];
            }
        );
    }
}
