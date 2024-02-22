<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class PaymentMethodController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'payment-method';
    }
}
