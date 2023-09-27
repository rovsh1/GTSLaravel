<?php

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Models\Supplier\Contact;
use App\Admin\Support\Http\Controllers\AbstractContactController;

class ContactController extends AbstractContactController
{
    protected function getPrototypeKey(): string
    {
        return 'supplier';
    }

    protected function getContactModel(): string
    {
        return Contact::class;
    }
}
