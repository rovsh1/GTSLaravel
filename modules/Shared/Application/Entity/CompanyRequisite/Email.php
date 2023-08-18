<?php

namespace Module\Shared\Application\Entity\CompanyRequisite;

use Module\Shared\Application\Support\CompanyRequisite\AbstractCompanyRequisite;

final class Email extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'email';

    public function name(): string
    {
        return 'Email';
    }

    public function default(): string
    {
        return 'info@gotostans.com';
    }
}
