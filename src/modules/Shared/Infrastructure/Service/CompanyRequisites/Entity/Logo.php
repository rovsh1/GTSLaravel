<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Illuminate\Support\Facades\Storage;
use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

final class Logo extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'image';

    public function name(): string
    {
        return 'Логотип';
    }

    public function default(): string
    {
        return Storage::disk('public')->url('company-logo-big.png');
    }
}
