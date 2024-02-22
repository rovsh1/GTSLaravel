<?php

namespace Services\CompanyRequisites\Entity;

use Services\CompanyRequisites\AbstractCompanyRequisite;

final class LegalAddress extends AbstractCompanyRequisite implements CompanyRequisiteInterface
{
    protected string $cast = 'address';

    public function name(): string
    {
        return 'Юр. Адрес компании';
    }

    public function default(): string
    {
        return 'Узбекистан, г.Ташкент, 100015, ул. Кичик Бешагач, д. 104А';
    }
}
