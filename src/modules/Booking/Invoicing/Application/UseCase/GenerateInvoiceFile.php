<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\UseCase;

use Module\Booking\Invoicing\Application\RequestDto\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Domain\Service\FileGenerator;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Dto\FileDto;

class GenerateInvoiceFile implements UseCaseInterface
{
    public function __construct(
        private readonly FileGenerator $fileGenerator
    ) {}

    public function execute(GenerateInvoiceFileRequestDto $request): FileDto
    {
        return $this->fileGenerator->generate(
            $request->filename,
            new OrderId($request->orderId),
            new ClientId($request->clientId),
        );
    }
}
