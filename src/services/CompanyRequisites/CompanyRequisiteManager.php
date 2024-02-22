<?php

namespace Services\CompanyRequisites;

use Sdk\Module\Support\ItemCollectionIteratorTrait;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;
use Services\CompanyRequisites\Entity\CompanyRequisiteInterface;

class CompanyRequisiteManager implements CompanyRequisitesInterface, \Iterator, \Countable
{
    use ItemCollectionIteratorTrait;

    public function __construct()
    {
        $this->items = (new CompanyRequisiteLoader())->load();
    }

    public function email(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Email::class)->value();
    }

    public function fax(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Fax::class)->value();
    }

    public function inn(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Inn::class)->value();
    }

    public function legalAddress(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\LegalAddress::class)->value();
    }

    public function logo(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Logo::class)->value();
    }

    public function name(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Name::class)->value();
    }

    public function oked(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Oked::class)->value();
    }

    public function phone(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Phone::class)->value();
    }

    public function region(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Region::class)->value();
    }

    public function signer(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\Signer::class)->value();
    }

    public function stampWithoutSign(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\StampWithoutSign::class)->value();
    }

    public function stampWithSign(): string
    {
        return $this->findByClass(\Services\CompanyRequisites\Entity\StampWithSign::class)->value();
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