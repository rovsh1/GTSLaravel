<?php

namespace Module\Administrator\Application\UseCase;

use App\Admin\Models\Administrator\Administrator;
use Module\Shared\Support\UseCase\AbstractUpdateModelFile;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateAvatar extends AbstractUpdateModelFile implements UseCaseInterface
{
    protected function model(): string
    {
        return Administrator::class;
    }

    protected function fileField(): string
    {
        return 'avatar';
    }
}
