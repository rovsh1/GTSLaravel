<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

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
