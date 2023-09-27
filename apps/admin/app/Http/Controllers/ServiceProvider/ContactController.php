<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Models\ServiceProvider\Contact;
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
