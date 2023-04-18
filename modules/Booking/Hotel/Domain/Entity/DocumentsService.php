<?php

namespace Module\Booking\Hotel\Domain\Entity;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Module\Booking\Hotel\Domain\Event;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator;
use Module\Booking\Hotel\Domain\Adapter\FileStorageAdapterInterface;
use Module\Booking\Hotel\Domain\Repository\RequestRepositoryInterface;

class DocumentsService
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $filesAdapter,
    ) {}

    public function sendReservationRequest(Reservation $reservation)
    {
        $request = $this->createDocument($reservation, ReservationRequest::class, \Module\Booking\Hotel\Domain\Service\DocumentGenerator\ReservationRequestGenerator::class);

        $this->eventDispatcher->dispatch(
            new \Module\Booking\Hotel\Domain\Event\ReservationRequestSent(
                $reservation->id(),
                $request->id(),
                $request->guid(),
            )
        );

        return $request;
    }

    public function sendVoucher($reservation)
    {
        $request = $this->createDocument($reservation, Voucher::class, \Module\Booking\Hotel\Domain\Service\DocumentGenerator\VoucherGenerator::class);

        //$this->eventDispatcher->dispatch(new Event\VoucherSent($reservation->id()));

        return $request;
    }

    private function createDocument(Reservation $reservation, string $documentClass, string $fileGeneratorClass)
    {
        $fileName = str_replace(__NAMESPACE__ . '\\', '', $documentClass);

        $fileDto = $this->filesAdapter->create(
            $documentClass,
            $reservation->id(),
            $this->getFileName($fileName),
            (new $fileGeneratorClass())->generate($reservation)
        );

        return $this->requestRepository->create(
            $reservation->id(),
            $documentClass,
            $fileDto->guid
        );
    }

    private function getFileName(string $prefix): string
    {
        return $prefix . '.pdf';
    }

    private function isRequestAvailable($reservation)
    {
        if (!$reservation->canRequest()) //TODO check status
            throw new \Exception('Reservation not found');
    }
}
