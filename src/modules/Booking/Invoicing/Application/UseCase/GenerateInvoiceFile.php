<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\UseCase;

use Module\Booking\Invoicing\Application\RequestDto\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Domain\Service\FileGenerator;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Service\ClientLocaleContext;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Dto\FileDto;

class GenerateInvoiceFile implements UseCaseInterface
{
    public function __construct(
        private readonly FileGenerator $fileGenerator,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ClientLocaleContext $clientLocaleDecorator,
    ) {}

    public function execute(GenerateInvoiceFileRequestDto $request): FileDto
    {
        $order = $this->orderRepository->findOrFail(new OrderId($request->orderId));

        return $this->clientLocaleDecorator->executeInClientLocale(
            $order->clientId(),
            fn() => $this->fileGenerator->generate($request->filename, $order->id())
        );
    }
}
