<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase\Document;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetDocumentFiles implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $documentId): Collection
    {
        return DB::table('client_document_files')
            ->where('document_id', $documentId)
            ->get()
            ->map(fn($r) => $this->fileStorageAdapter->find($r->guid));
    }
}
