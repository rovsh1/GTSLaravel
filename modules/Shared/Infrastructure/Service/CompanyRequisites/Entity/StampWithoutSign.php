<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Illuminate\Support\Facades\Storage;
use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

final class StampWithoutSign extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'image';

    public function name(): string
    {
        return 'Штамп без подписи';
    }

    public function default(): string
    {
        return Storage::disk('public')->url('company-stamp-without-sign.png');
    }
}
