<?php

namespace App\Site\Models;

class Client extends \Module\Client\Shared\Infrastructure\Models\Client
{
    public function __toString()
    {
        return (string)$this->name;
    }
}
