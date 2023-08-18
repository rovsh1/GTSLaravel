<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class Region extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    public function name(): string
    {
        return 'Город, страна';
    }

    public function default(): string
    {
        return 'Ташкент, Узбекистан';
    }
}
