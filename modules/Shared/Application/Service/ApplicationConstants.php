<?php

namespace Module\Shared\Application\Service;

use Module\Shared\Application\Entity\Constant\ConstantInterface;
use Module\Shared\Application\Support\Constant\ConstantLoader;
use Sdk\Module\Support\ItemCollectionIteratorTrait;

/**
 * @method float basicCalculatedValue()
 * @method static float basicCalculatedValue()
 */
class ApplicationConstants implements \Iterator, \Countable
{
    use ItemCollectionIteratorTrait;

    public static function getInstance(): static
    {
        return new static();
    }

    private function __construct()
    {
        $this->items = (new ConstantLoader())->load();
    }

    public function __call(string $name, array $arguments)
    {
        return $this->findByClass('Module\Shared\Application\Entity\Constant\\' . ucfirst($name))->value();
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return self::getInstance()->$name(...$arguments);
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