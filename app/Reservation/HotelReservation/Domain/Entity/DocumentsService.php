<?php

namespace GTS\Reservation\HotelReservation\Domain\Entity;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use GTS\Reservation\HotelReservation\Domain\Adapter\FileStorageAdapterInterface;
use GTS\Reservation\HotelReservation\Domain\Event;
use GTS\Reservation\HotelReservation\Domain\Repository\RequestRepositoryInterface;
use GTS\Reservation\HotelReservation\Domain\Service\DocumentGenerator;

class DocumentsService
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly FileStorageAdapterInterface $filesAdapter,
    ) {}

    public function sendReservationRequest(Reservation $reservation)
    {
        $request = $this->createDocument($reservation, ReservationRequest::class, DocumentGenerator\ReservationRequestGenerator::class);

        $this->eventDispatcher->dispatch(
            new Event\ReservationRequestSent(
                $reservation->id(),
                $request->id(),
                $request->guid(),
            )
        );

        return $request;
    }

    public function sendVoucher($reservation)
    {
        $request = $this->createDocument($reservation, Voucher::class, DocumentGenerator\VoucherGenerator::class);

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
