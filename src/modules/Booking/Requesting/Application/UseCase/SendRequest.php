<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Application\UseCase;

use Module\Booking\Requesting\Domain\BookingRequest\Factory\RequestFactory;
use Module\Booking\Requesting\Domain\BookingRequest\Service\RequestRules;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendRequest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RequestFactory $requestFactory,
        private readonly RequestRules $requestRules,
    ) {
    }

    public function execute(int $id): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($id));

        $this->requestRules->booking($booking);

        $this->requestFactory->generate($booking, $this->requestRules->getRequestType());
    }
}
