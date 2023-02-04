<?php

namespace GTS\Reservation\Domain\Entity\Document;

class Invoice
{
    public function __construct(private array $reserationsIds) {}
}
