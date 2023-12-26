<?php

namespace Module\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface DescriptorInterface
{
    public function build(IntegrationEventInterface $event): DescriptionDto;
}