<?php

namespace App\Admin\Components\Prototype;

class PrototypeManager
{
    private array $prototypes = [];

    public function __construct() {}

    public function all(): array
    {
        return $this->prototypes;
    }

    public function add(Prototype $prototype): void
    {
        $this->prototypes[] = $prototype;
    }

    public function has(string $key): bool
    {
        foreach ($this->prototypes as $prototype) {
            if ($prototype->is($key)) {
                return true;
            }
        }
        return false;
    }

    public function get(string $key): ?Prototype
    {
        foreach ($this->prototypes as $prototype) {
            if ($prototype->is($key)) {
                return $prototype;
            }
        }
        return null;
    }
}
