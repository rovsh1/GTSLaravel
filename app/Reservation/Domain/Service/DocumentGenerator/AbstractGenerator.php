<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

abstract class AbstractGenerator
{
    public function __construct(private readonly DocumentFactoryInterface $documentFactory) {}
}
