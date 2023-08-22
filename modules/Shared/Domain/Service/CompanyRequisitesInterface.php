<?php

namespace Module\Shared\Domain\Service;

interface CompanyRequisitesInterface
{
    public function email(): string;

    public function fax(): string;

    public function inn(): string;

    public function legalAddress(): string;

    public function logo(): string;

    public function name(): string;

    public function oked(): string;

    public function phone(): string;

    public function region(): string;

    public function signer(): string;

    public function stampWithoutSign(): string;

    public function stampWithSign(): string;
}