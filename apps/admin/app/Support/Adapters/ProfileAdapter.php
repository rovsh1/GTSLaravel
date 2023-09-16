<?php

namespace App\Admin\Support\Adapters;

use App\Admin\Models\Administrator\Administrator;
use App\Shared\Support\Facades\FileStorage;
use Illuminate\Support\Facades\Auth;
use Module\Shared\Dto\FileDto;

class ProfileAdapter
{
    public function user(): Administrator
    {
        return Auth::user();
    }

    public function avatar(): ?FileDto
    {
        return ($guid = $this->user()->avatar_guid)
            ? FileStorage::find($guid)
            : null;
    }
}