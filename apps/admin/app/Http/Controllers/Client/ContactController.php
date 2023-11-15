<?php

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Models\Client\Contact;
use App\Admin\Support\Http\Controllers\AbstractContactController;

class ContactController extends AbstractContactController
{
    protected function getPrototypeKey(): string
    {
        return 'client';
    }

    protected function getContactModel(): string
    {
        return Contact::class;
    }

    protected function getParentIdFieldName(): string
    {
        return 'client_id';
    }
}
