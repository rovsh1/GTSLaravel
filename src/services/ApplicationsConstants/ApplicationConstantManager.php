<?php

namespace Services\ApplicationsConstants;

use Sdk\Module\Support\ItemCollectionIteratorTrait;
use Sdk\Shared\Contracts\Service\ApplicationConstantsInterface;
use Services\ApplicationsConstants\Constant\BasicCalculatedValue;
use Services\ApplicationsConstants\Constant\ConstantInterface;

class ApplicationConstantManager implements ApplicationConstantsInterface
{
    use ItemCollectionIteratorTrait;

    public function __construct()
    {
        $this->items = (new ConstantLoader())->load();
    }

    public function basicCalculatedValue(): float
    {
        return $this->findByClass(BasicCalculatedValue::class)->value();
    }

    public function count(): int
    {
        return count($this->items);
    }

    private function findByClass(string $class): ConstantInterface
    {
        foreach ($this->items as $constant) {
            if (is_a($constant, $class)) {
                return $constant;
            }
        }

        throw new \LogicException("Constant $class not implemented");
    }
}
