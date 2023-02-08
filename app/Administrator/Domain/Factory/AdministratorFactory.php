<?php

namespace GTS\Administrator\Domain\Factory;

use GTS\Administrator\Domain\Entity\Administrator;

class AdministratorFactory
{
    public static function fromArray(array $data): Administrator
    {
        return new Administrator(
            $data['id'],
            $data['presentation']
        );
    }
}
