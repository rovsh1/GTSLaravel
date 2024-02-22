<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Models\Hotel\Contact;
use App\Admin\Support\Http\Controllers\AbstractContactController;

class ContactController extends AbstractContactController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel';
    }

    protected function getContactModel(): string
    {
        return Contact::class;
    }

    protected function getParentIdFieldName(): string
    {
        return 'hotel_id';
    }
}
