<?php

namespace Pkg\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

interface DescriptorInterface
{
    public function build(IntegrationEventInterface $event): DescriptionDto;
}