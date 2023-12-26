<?php

namespace Module\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Module\Booking\Requesting\Domain\Service\ChangesStorageInterface;

abstract class AbstractRegistrar implements RegistrarInterface
{
    protected const DIFF_SEPARATOR = '→';

    public function __construct(
        protected readonly ChangesStorageInterface $changesStorage
    ) {}
}