<?php

namespace Services\CompanyRequisites\Entity;

use Illuminate\Support\Facades\Storage;
use Services\CompanyRequisites\AbstractCompanyRequisite;

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
