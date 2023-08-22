<?php

namespace Module\Shared\Infrastructure\Service\CompanyRequisites\Entity;

use Module\Shared\Infrastructure\Service\CompanyRequisites\AbstractCompanyRequisite;

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
