<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class Fax extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'fax';

    public function name(): string
    {
        return 'Факс';
    }

    public function default(): string
    {
        return '+998711209013';
    }
}
