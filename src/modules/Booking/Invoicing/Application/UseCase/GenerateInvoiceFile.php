<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\UseCase;

use Module\Booking\Invoicing\Application\RequestDto\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Domain\Service\FileGenerator;
use Module\Booking\Invoicing\Domain\ValueObject\InvoiceId;
use Module\Booking\Invoicing\Domain\ValueObject\OrderIdCollection;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Shared\Dto\FileDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GenerateInvoiceFile implements UseCaseInterface
{
    public function __construct(
        private readonly FileGenerator $fileGenerator
    ) {}

    public function execute(GenerateInvoiceFileRequestDto $request): FileDto
    {
        $orderIds = new OrderIdCollection(
            array_map(fn(int $orderId) => new OrderId($orderId), $request->orderIds)
        );

        return $this->fileGenerator->generate(
            $request->filename,
            new InvoiceId($request->invoiceId),
            $orderIds,
            new ClientId($request->clientId),
            $request->createdAt
        );
    }
}
