<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

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
