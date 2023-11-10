<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service;

use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\CarDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\DetailOptionDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking\PriceDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\BookingDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\ClientDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\CompanyRequisitesDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\InvoiceDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\ManagerDto;
use Module\Client\Invoicing\Domain\Invoice\Service\Dto\OrderDto;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;

class TemplateDataFactory
{
    private array $countryNamesIndexedId;

    public function __construct(
        CountryAdapterInterface $countryAdapter,
        private readonly CompanyRequisitesInterface $companyRequisites,
//        private readonly AdministratorAdapterInterface $administratorAdapter,
//        private readonly ClientAdapterInterface $clientAdapter,
//        private readonly BookingRepositoryInterface $bookingRepository,
//        private readonly OrderStatusStorageInterface $orderStatusStorage,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
        $countries = $countryAdapter->get();
        $this->countryNamesIndexedId = collect($countries)->keyBy('id')->map->name->all();
    }

    public function build(ClientId $clientId, OrderId $orderId): array
    {
        $order = $this->orderRepository->findOrFail($orderId);

        return [
            'order' => $this->buildOrderDto($order),
            'bookings' => $this->buildBookings($orderId),
            'company' => $this->getCompanyRequisites(),
            'manager' => $this->buildManagerDto($order),
            'client' => $this->buildClientDto($clientId),
            'invoice' => $this->buildInvoiceDto(),
        ];
    }

    private function buildInvoiceDto(): InvoiceDto
    {
        return new InvoiceDto(
            '123',
            now()->toDateString(),
        );
    }

    /**
     * @param OrderId $orderId
     * @return BookingDto[]
     */
    private function buildBookings(OrderId $orderId): array
    {
//        $bookings = $this->bookingRepository->getByOrderId($orderId);
        $bookings = [1, 2, 3];

        return array_map(fn($booking) => $this->buildBooking(), $bookings);
    }

    private function buildBooking(): BookingDto
    {
        $details = collect([
            DetailOptionDto::createDate('Дата прилёта', '18.04.2023'),
            DetailOptionDto::createTime('Время прилета', '12:00'),
            DetailOptionDto::createText('Номер рейса', 'sa347'),
            DetailOptionDto::createText('Город прилета', 'Ташкент'),
            DetailOptionDto::createText('Табличка для встречи', 'Привет!'),
        ]);
        $cars = [
            new CarDto('Chevrolet', 'Cobalt', 1),
        ];

        return new BookingDto(
            '12345',
            'Встреча в Международном Аэропорту (Ташкент)',
            $details,
            new PriceDto(22, 'USD'),
            null,
            null,
            $cars,
            null
        );
    }

    private function buildClientDto(ClientId $clientId): ClientDto
    {
//        $client = $this->clientAdapter->find($clientId->value());

        return new ClientDto(
            'Имя Фамилия',
            '7777777777',
            'Адрес такойто'
        );
    }

    private function buildOrderDto(Order $order): OrderDto
    {
//        $statusName = $this->orderStatusStorage->getName($order->status());

        return new OrderDto(
            (string)$order->id()->value(),
            'Выставлен счёт',
            $order->currency()->name
        );
    }

    private function buildManagerDto(Order $order): ManagerDto
    {
        return new ManagerDto('Менеджер', null, null);

        $managerDto = $this->administratorAdapter->getOrderAdministrator($order->id());

        return new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
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
