<?php

namespace GTS\Reservation\HotelReservation\Domain\Entity;

use GTS\Reservation\HotelReservation\Domain\Event;
use GTS\Reservation\HotelReservation\Domain\Repository\RequestRepositoryInterface;
use GTS\Reservation\HotelReservation\Domain\Service\DocumentGenerator;
use GTS\Services\PortGateway\GatewayInterface;
use GTS\Shared\Domain\Event\DomainEventDispatcherInterface;

class DocumentsService
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly GatewayInterface $portGateway,
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

        $fileDto = $this->portGateway->call('fileStorage.create', [
            'type' => $documentClass,
            'entity_id' => $reservation->id(),
            'name' => $this->getFileName($fileName),
            'contents' => (new $fileGeneratorClass())->generate($reservation),
        ]);

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
