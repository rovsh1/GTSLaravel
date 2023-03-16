<?php

namespace App\Admin\Components\Factory;

use App\Admin\Support\Repository\RepositoryInterface;

class FactoryManager
{
    private PrototypesCollection $prototypes;

    public function __construct()
    {
        $this->prototypes = new PrototypesCollection();
    }

    public function makeRepository(string|Prototype $prototype): RepositoryInterface
    {
        if (is_string($prototype)) {
            $prototype = $this->prototypes->get($prototype);
        }

        return $prototype->makeRepository();
    }

    public function prototypes(): PrototypesCollection
    {
        return $this->prototypes;
    }
}
