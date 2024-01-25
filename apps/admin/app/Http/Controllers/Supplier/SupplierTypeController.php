<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class SupplierTypeController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'supplier-type';
    }

    protected function hasShowAction(): bool
    {
        return false;
    }
}
