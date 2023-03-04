<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Resources\Country\CountriesResource;
use App\Admin\Http\Forms\Country\EditForm;
use App\Admin\Support\Http\Controllers\AbstractResourceController;

class CountryController extends AbstractResourceController
{
    protected $resource = 'reference.country';

    protected function indexResourceFactory()
    {
        return new CountriesResource();
    }

    protected function formFactory()
    {
        return new EditForm('data');
    }
}
