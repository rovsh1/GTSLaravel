<?php

namespace App\Admin\Support\Facades;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Adapters\ProfileAdapter;
use Illuminate\Support\Facades\Facade;
use Module\Shared\Dto\FileDto;

/**
 * @method static Administrator user()
 * @method static FileDto|null avatar()
 */
class Profile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProfileAdapter::class;
    }
}
