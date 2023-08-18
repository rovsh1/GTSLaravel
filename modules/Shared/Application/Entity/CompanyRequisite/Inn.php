<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class Inn extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'inn';

    public function name(): string
    {
        return 'ИНН';
    }

    public function default(): string
    {
        return '305768069';
    }
}
