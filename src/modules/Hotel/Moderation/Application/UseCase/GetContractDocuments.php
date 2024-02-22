<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;

class GetContractDocuments implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {
    }

    public function execute(int $contractId): Collection
    {
        return DB::table('hotel_contract_documents')
            ->where('contract_id', $contractId)
            ->get()
            ->map(fn($r) => $this->fileStorageAdapter->find($r->guid));
    }
}
