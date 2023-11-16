<?php

declare(strict_types=1);

namespace App\Admin\Models\Finance;

use App\Shared\Support\Facades\FileStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Module\Client\Payment\Domain\Payment\ValueObject\PaymentStatusEnum;
use Module\Shared\Dto\FileDto;
use Module\Shared\Dto\UploadedFileDto;

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
                ->addSelect('clients.name as client_name');
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
