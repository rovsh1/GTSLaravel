<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class StatusTransitionsFactory
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function build(ServiceTypeEnum $serviceType): StatusTransitionsInterface
    {
        return match ($serviceType) {
            ServiceTypeEnum::OTHER_SERVICE => $this->container->make(OtherServiceTransitions::class),
            default => $this->container->make(DefaultTransitions::class)
        };
    }
}
