<?php

namespace Services\CompanyRequisites\Entity;

use Services\CompanyRequisites\AbstractCompanyRequisite;

final class Oked extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    public function name(): string
    {
        return 'ОКЭД';
    }

    public function default(): string
    {
        return '79900';
    }
}
