<?php

namespace Module\Shared\Infrastructure\Service\ApplicationsConstants;

use Module\Shared\Domain\Service\ApplicationConstantsInterface;
use Module\Shared\Infrastructure\Service\ApplicationsConstants\Constant\ConstantInterface;
use Sdk\Module\Support\ItemCollectionIteratorTrait;

class ApplicationConstantManager implements ApplicationConstantsInterface, \Iterator, \Countable
{
    use ItemCollectionIteratorTrait;

    public function __construct()
    {
        $this->items = (new ConstantLoader())->load();
    }

    public function basicCalculatedValue(): float
    {
        return $this->findByClass(Constant\BasicCalculatedValue::class)->value();
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