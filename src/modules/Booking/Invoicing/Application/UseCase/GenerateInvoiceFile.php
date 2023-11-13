<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\UseCase;

use Module\Booking\Invoicing\Application\Request\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Domain\Service\FileGenerator;
use Module\Booking\Invoicing\Domain\ValueObject\InvoiceId;
use Module\Shared\Dto\FileDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GenerateInvoiceFile implements UseCaseInterface
{
    public function __construct(
        private readonly FileGenerator $fileGenerator
    ) {}

    public function execute(GenerateInvoiceFileRequestDto $request): FileDto
    {
        return $this->fileGenerator->generate(
            $request->filename,
            new InvoiceId($request->invoiceId),
        );
    }
}
