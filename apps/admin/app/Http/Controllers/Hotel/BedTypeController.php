<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class BedTypeController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-bed-type';
    }
}
