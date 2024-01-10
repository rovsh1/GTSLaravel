<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service;

use App\Admin\Support\Facades\Format;
use Module\Booking\Invoicing\Domain\Repository\InvoiceRepositoryInterface;
use Module\Booking\Invoicing\Domain\Service\Dto\ClientDto;
use Module\Booking\Invoicing\Domain\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Invoicing\Domain\Service\Dto\InvoiceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ManagerDto;
use Module\Booking\Invoicing\Domain\Service\Dto\OrderDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use Module\Booking\Invoicing\Domain\Service\Factory\ServiceInfoDataFactory;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;

class TemplateDataFactory
{
    public function __construct(
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ServiceInfoDataFactory $serviceInfoDataFactory,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
    ) {}

    public function build(OrderId $orderId): array
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $services = $this->serviceInfoDataFactory->build($order->id());

        return [
            'order' => $this->buildOrderDto($order),
            'services' => $services,
            'company' => $this->getCompanyRequisites(),
            'manager' => $this->buildOrderManagerDto($order->id()),
            'client' => $this->buildClientDto($order->clientId()),
            'invoice' => $this->buildInvoiceDto($order->id(), now(), $services),
        ];
    }

    /**
     * @param OrderId $id
     * @param \DateTimeInterface $createdAt
     * @param ServiceInfoDto[] $services
     * @return InvoiceDto
     */
    private function buildInvoiceDto(OrderId $id, \DateTimeInterface $createdAt, array $services): InvoiceDto
    {
        /** @var float $totalAmount */
        $totalAmount = collect($services)->reduce(
            fn(float $value, ServiceInfoDto $serviceInfoDto) => $value + $serviceInfoDto->price->total,
            0
        );
        /** @var float $totalPenalty */
        $totalPenalty = collect($services)->reduce(
            fn(float $value, ServiceInfoDto $serviceInfoDto) => $value + $serviceInfoDto->price->penalty,
            0
        );
        if ($totalPenalty === 0) {
            $totalPenalty = null;
        }

        $file = null;
        $fileGuid = $this->invoiceRepository->getInvoiceFileGuid($id);
        if ($fileGuid !== null) {
            $file = $this->fileStorageAdapter->find($fileGuid);
        }

        return new InvoiceDto(
            (string)$id->value(),
            $createdAt->format('d.m.Y H:i'),
            Format::price($totalAmount),
            Format::price($totalPenalty),
            $file?->url,
        );
    }

    private function buildClientDto(ClientId $clientId): ClientDto
    {
        $client = $this->clientAdapter->find($clientId->value());
        $clientContract = $this->clientAdapter->findContract($clientId->value());

        return new ClientDto(
            $client->name,
            $client->phone,
            $client->email,
            $client->address,
            $clientContract?->number,
        );
    }

    private function buildOrderDto(Order $order): OrderDto
    {
        return new OrderDto(
            (string)$order->id()->value(),
            $order->currency()->name
        );
    }

    private function buildOrderManagerDto(OrderId $orderId): ManagerDto
    {
        $managerDto = $this->administratorAdapter->getOrderAdministrator($orderId);

        return new ManagerDto(
            $managerDto->name ?? $managerDto?->presentation,
            $managerDto?->email,
            $managerDto?->phone,
            $managerDto?->post,
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
