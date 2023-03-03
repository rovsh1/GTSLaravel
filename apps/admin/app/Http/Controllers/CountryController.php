<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Resources\Country\CountriesResource;
use App\Admin\Support\Http\Controllers\AbstractCrudController;
use App\Admin\Http\Forms\Country\EditForm;
use App\Admin\Models\Country;

class CountryController extends AbstractCrudController
{
    protected string $model = Country::class;

    protected array $titles = [
        'index' => 'Страны',
        'create' => 'Новая страна'
    ];

    protected array $views = [
        'form' => 'reference.country.form'
    ];

    protected function indexResourceFactory()
    {
        return new CountriesResource();
    }

    protected function formFactory()
    {
        return new EditForm('data');
    }
}
