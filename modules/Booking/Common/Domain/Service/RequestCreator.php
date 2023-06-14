<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;

class RequestCreator
{
    public function __construct(
        private readonly DocumentGeneratorFactory $documentGeneratorFactory,
        private readonly RequestRepositoryInterface $requestRepository,
    ) {}

    public function create(AbstractBooking $booking, RequestRules $rules): Request
    {
        $requestType = $rules->getRequestTypeByStatus($booking->status());
        $request = $this->requestRepository->create($booking->id()->value(), $requestType);
        $documentGenerator = $this->documentGeneratorFactory->getGenerator($request, $booking);
        $documentGenerator->generate($request, $booking);

        return $request;
    }
}
