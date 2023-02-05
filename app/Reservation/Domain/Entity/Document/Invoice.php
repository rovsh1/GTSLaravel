<?php

namespace GTS\Reservation\Domain\Entity\Document;

class Invoice implements DocumentInterface
{
    public function __construct(private array $reserationsIds) {}
}
