<?php

namespace App\Admin\Repositories;

use App\Admin\Contracts\Repository\CrudRepositoryInterface;
use App\Admin\Models\Country;

class CountryRepository implements CrudRepositoryInterface
{
    public function findById(int $id): ?Country
    {
        return Country::find($id);
    }
}
