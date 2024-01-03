<?php

namespace Services\CompanyRequisites\Entity;

use Services\CompanyRequisites\AbstractCompanyRequisite;

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
