<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

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
