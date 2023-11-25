<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager;

use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Quota;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Request;
use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Site;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class QuotaProcessingMethodFactory
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function build(QuotaProcessingMethodEnum $method): QuotaProcessingMethodInterface
    {
        return match ($method) {
            QuotaProcessingMethodEnum::QUOTA => $this->container->make(Quota::class),
            QuotaProcessingMethodEnum::REQUEST => new Request(),
            QuotaProcessingMethodEnum::SITE => new Site(),
        };
    }
}
