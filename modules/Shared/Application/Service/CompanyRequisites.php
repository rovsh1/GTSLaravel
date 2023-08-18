<?php

namespace Module\Shared\Application\Service;

use Module\Shared\Application\Entity\CompanyRequisite\CompanyRequisiteInterface;
use Module\Shared\Application\Support\CompanyRequisite\CompanyRequisiteLoader;
use Sdk\Module\Support\ItemCollectionIteratorTrait;

/**
 * @method string email()
 * @method string fax()
 * @method string inn()
 * @method string legalAddress()
 * @method string logo()
 * @method string name()
 * @method string oked()
 * @method string phone()
 * @method string region()
 * @method string signer()
 * @method string stampWithoutSign()
 * @method string stampWithSign()
 * @method static string email()
 * @method static string fax()
 * @method static string inn()
 * @method static string legalAddress()
 * @method static string logo()
 * @method static string name()
 * @method static string oked()
 * @method static string phone()
 * @method static string region()
 * @method static string signer()
 * @method static string stampWithoutSign()
 * @method static string stampWithSign()
 */
class CompanyRequisites implements \Iterator, \Countable
{
    use ItemCollectionIteratorTrait;

    public static function getInstance(): static
    {
        return new static();
    }

    private function __construct()
    {
        $this->items = (new CompanyRequisiteLoader())->load();
    }

    public function __call(string $name, array $arguments)
    {
        return $this->findByClass('Module\Shared\Application\Entity\CompanyRequisite\\' . ucfirst($name))->value();
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return self::getInstance()->$name();
    }

    public function count(): int
    {
        return count($this->items);
    }

    private function findByClass(string $class): CompanyRequisiteInterface
    {
        foreach ($this->items as $constant) {
            if (is_a($constant, $class)) {
                return $constant;
            }
        }

        throw new \LogicException("CompanyRequisite $class not implemented");
    }
}