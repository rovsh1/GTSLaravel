<?php

declare(strict_types=1);

namespace App\Admin\Models\Finance;

use App\Shared\Support\Facades\FileStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Http\UploadedFile;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Dto\UploadedFileDto;

class Payment extends \Module\Client\Payment\Infrastructure\Models\Payment
{
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
            $builder->addSelect('client_payments.*')
                ->join('clients', 'clients.id', 'client_payments.client_id')
                ->addSelect('clients.name as client_name')
                ->selectSub(function (Query $query) {
                    $query->selectRaw('SUM(sum)')
                        ->from('client_payment_plants')
                        ->whereColumn('client_payment_plants.payment_id', 'client_payments.id');
                }, 'lend_sum')
                ->selectSub(function (Query $query) {
                    $query->selectRaw('client_payments.payment_sum - SUM(sum)')
                        ->from('client_payment_plants')
                        ->whereColumn('client_payment_plants.payment_id', 'client_payments.id');
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
