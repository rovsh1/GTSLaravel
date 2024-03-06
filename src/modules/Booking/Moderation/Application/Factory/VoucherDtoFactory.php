<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Carbon\CarbonImmutable;
use Module\Booking\Moderation\Application\Dto\VoucherDto;
use Module\Booking\Moderation\Domain\Order\ValueObject\Voucher;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class VoucherDtoFactory
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function createFromEntity(Voucher $voucher): VoucherDto
    {
        $fileDto = $this->fileStorageAdapter->find($voucher->file()->guid());
        $wordFileDto = null;
        if ($voucher->wordFile() !== null) {
            $wordFileDto = $this->fileStorageAdapter->find($voucher->wordFile()->guid());
        }

        return new VoucherDto(
            createdAt: new CarbonImmutable($voucher->createdAt()),
            file: $fileDto,
            wordFile: $wordFileDto,
            sendAt: $voucher->sendAt() !== null ? new CarbonImmutable($voucher->sendAt()) : null,
        );
    }
}
