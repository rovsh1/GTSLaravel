<?php

namespace Services\CompanyRequisites\Entity;

use Services\CompanyRequisites\AbstractCompanyRequisite;

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
