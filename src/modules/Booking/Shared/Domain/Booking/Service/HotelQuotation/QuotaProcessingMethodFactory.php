<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelQuotation;

use Module\Booking\Shared\Domain\Booking\Service\HotelQuotation\ProcessingMethod\Quota;
use Module\Booking\Shared\Domain\Booking\Service\HotelQuotation\ProcessingMethod\Request;
use Module\Booking\Shared\Domain\Booking\Service\HotelQuotation\ProcessingMethod\Site;
use Sdk\Booking\Enum\QuotaProcessingMethodEnum;
use Sdk\Module\Contracts\Support\ContainerInterface;

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
