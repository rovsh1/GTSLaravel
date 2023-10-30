<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

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
