<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase\Document;

use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\UploadedFileDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UploadDocumentFiles implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $documentId, array $files): void
    {
        $values = [];
        /** @var UploadedFileDto $uploadedFileDto */
        foreach ($files as $uploadedFileDto) {
            $fileDto = $this->fileStorageAdapter->create($uploadedFileDto->name, $uploadedFileDto->contents);
            $values[] = ['document_id' => $documentId, 'guid' => $fileDto->guid];
        }

        if ($values) {
            DB::table('client_document_files')->insert($values);
        }
    }
}
