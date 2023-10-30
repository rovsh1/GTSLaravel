<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase\Request;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\BookingRequest\Factory\RequestFactory;
use Module\Booking\Domain\BookingRequest\Service\RequestRules;
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

        $requestType = $this->requestRules->getRequestTypeByStatus($booking->status());

        $this->requestFactory->generate($booking, $requestType);
    }
}
