<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator;

use Illuminate\Support\Facades\App;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\ClientDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\CompanyRequisitesDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\ManagerDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\OrderDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\ServiceInfoDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\VoucherDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\ServiceInfoDataFactory;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Service\CompanyRequisitesInterface;

class TemplateDataFactory
{
    public function __construct(
        private readonly CompanyRequisitesInterface $companyRequisites,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ServiceInfoDataFactory $serviceInfoDataFactory,
    ) {}

    public function build(OrderId $orderId): array
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $services = $this->serviceInfoDataFactory->build($orderId);

        return [
            'order' => $this->buildOrderDto($order),
            'services' => $services,
            'company' => $this->getCompanyRequisites(),
            'manager' => $this->buildOrderManagerDto($orderId),
            'client' => $this->buildClientDto($order->clientId()),
            'voucher' => $this->buildVoucherDto($orderId, now()),
        ];
    }

    /**
     * @param OrderId $id
     * @param \DateTimeInterface $createdAt
     * @param ServiceInfoDto[] $services
     * @return VoucherDto
     */
    private function buildVoucherDto(OrderId $id, \DateTimeInterface $createdAt): VoucherDto
    {
        return new VoucherDto(
            (string)$id->value(),
            $createdAt->format('d.m.Y H:i'),
        );
    }

    private function buildClientDto(ClientId $clientId): ClientDto
    {
        $client = $this->clientAdapter->find($clientId->value());

        return new ClientDto(
            $client->name,
            $client->phone,
            $client->email,
            $client->address,
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
