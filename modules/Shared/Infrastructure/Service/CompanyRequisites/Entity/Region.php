<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

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
