<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase\Document;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

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
