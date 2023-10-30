<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Illuminate\Support\Facades\Storage;
use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

final class StampWithSign extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'image';

    public function name(): string
    {
        return 'Штамп с подписью';
    }

    public function default(): string
    {
        return Storage::disk('public')->url('company-stamp-with-sign.png');
    }
}
