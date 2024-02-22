<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class BedTypeController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-bed-type';
    }
}
