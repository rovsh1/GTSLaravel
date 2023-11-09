<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\UploadedFileDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UploadContractDocuments implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $contractId, array $files): void
    {
        $values = [];
        /** @var UploadedFileDto $uploadedFileDto */
        foreach ($files as $uploadedFileDto) {
            $fileDto = $this->fileStorageAdapter->create($uploadedFileDto->name, $uploadedFileDto->contents);
            $values[] = ['contract_id' => $contractId, 'guid' => $fileDto->guid];
        }

        if ($values) {
            DB::table('hotel_contract_documents')->insert($values);
        }
    }
}
