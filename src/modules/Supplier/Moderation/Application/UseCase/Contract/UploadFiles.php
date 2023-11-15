<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\Contract;

use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\UploadedFileDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UploadFiles implements UseCaseInterface
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(int $contractId, array $files): void
    {
        $existFilesQuery = DB::table('supplier_contract_files')->where('contract_id', $contractId);
        $guids = $existFilesQuery->get()->pluck('guid')->toArray();
        $existFilesQuery->delete();
        foreach ($guids as $guid) {
            $this->fileStorageAdapter->delete($guid);
        }

        $values = [];
        /** @var UploadedFileDto $uploadedFileDto */
        foreach ($files as $uploadedFileDto) {
            $fileDto = $this->fileStorageAdapter->create($uploadedFileDto->name, $uploadedFileDto->contents);
            $values[] = ['contract_id' => $contractId, 'guid' => $fileDto->guid];
        }

        if ($values) {
            DB::table('supplier_contract_files')->insert($values);
        }
    }
}
