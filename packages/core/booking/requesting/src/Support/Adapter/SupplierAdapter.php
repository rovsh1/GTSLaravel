<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Support\Adapter;

use Illuminate\Support\Facades\DB;
use Pkg\Booking\Requesting\Domain\Adapter\SupplierAdapterInterface;
use Sdk\Shared\Enum\ContactTypeEnum;

class SupplierAdapter implements SupplierAdapterInterface
{
    public function getEmail(int $supplierId): ?string
    {
        $contact = DB::table('supplier_contacts')
            ->where('supplier_id', $supplierId)
            ->where('type', ContactTypeEnum::EMAIL)
            ->where('is_main', true)->first();

        return $contact?->value;
    }
}
