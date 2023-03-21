<?php

namespace App\Admin\Http\Controllers\ServiceProvider;

use App\Admin\Models\ServiceProvider\Contact;
use App\Admin\Support\Http\Controllers\AbstractContactController;

class ContactController extends AbstractContactController
{
    protected string $contactModel = Contact::class;

    protected string $routePath = 'service-provider';
}
