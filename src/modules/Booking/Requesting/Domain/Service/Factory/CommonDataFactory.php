<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service\Factory;

use Module\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Requesting\Domain\Service\Dto\BookingDto;
use Module\Booking\Requesting\Domain\Service\Dto\BookingPriceDto;
use Module\Booking\Requesting\Domain\Service\Dto\ClientDto;
use Module\Booking\Requesting\Domain\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Requesting\Domain\Service\Dto\ManagerDto;
use Module\Booking\Requesting\Domain\Service\TemplateData\CommonData;
use Module\Booking\Requesting\Domain\Service\TemplateDataInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;

class CommonDataFactory
{
    public function __construct(
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly BookingStatusStorageInterface $statusStorage,
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}


    public function build(Booking $booking): TemplateDataInterface
    {
        return new CommonData(
            $this->buildBookingDto($booking),
            $this->getCompanyRequisites(),
            $this->buildManagerDto($booking),
            $this->buildClientDto($booking),
        );
    }

    private function buildClientDto(Booking $booking): ClientDto
    {
        $order = $this->orderRepository->findOrFail($booking->orderId());
        $client = $this->clientAdapter->find($order->clientId()->value());

        return new ClientDto(
            $client->name
        );
    }

    private function buildManagerDto(Booking $booking): ManagerDto
    {
        $managerDto = $this->administratorAdapter->getManagerByBookingId($booking->id()->value());

        return new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
        );
    }

    private function buildBookingDto(Booking $booking): BookingDto
    {
        $clientPriceAmount = $booking->prices()->clientPrice()->manualValue() ?? $booking->prices()->clientPrice()->calculatedValue();
        $supplierPriceAmount = $booking->prices()->supplierPrice()->manualValue() ?? $booking->prices()->supplierPrice()->calculatedValue();

        return new BookingDto(
            number: $booking->id()->value(),
            status: $this->statusStorage->getName($booking->status()->value()),
            createdAt: $booking->timestamps()->createdAt()->format('d.m.Y H:i:s'),
            updatedAt: $booking->timestamps()->updatedAt()->format('d.m.Y H:i:s'),
            clientPrice: new BookingPriceDto(
                $clientPriceAmount,
                $booking->prices()->clientPrice()->currency()->name,
            ),
            supplierPrice: new BookingPriceDto(
                $supplierPriceAmount,
                $booking->prices()->supplierPrice()->currency()->name,
            ),
            note: $booking->note(),
        );
    }

    private function getCompanyRequisites(): CompanyRequisitesDto
    {
        return new CompanyRequisitesDto(
            name: $this->companyRequisites->name(),
            phone: $this->companyRequisites->phone(),
            email: $this->companyRequisites->email(),
            legalAddress: $this->companyRequisites->legalAddress(),
            signer: $this->companyRequisites->signer(),
            region: $this->companyRequisites->region(),
        );
    }
}
