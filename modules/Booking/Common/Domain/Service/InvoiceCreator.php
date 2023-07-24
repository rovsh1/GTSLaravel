<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Entity\Invoice;
use Module\Booking\Common\Domain\Repository\InvoiceRepositoryInterface;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\InvoiceGenerator;

class InvoiceCreator
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $repository,
        private readonly InvoiceGenerator $invoiceGenerator
    ) {}

    public function create(BookingInterface $booking): Invoice
    {
        $invoice = $this->repository->create($booking->id()->value());
        $this->invoiceGenerator->generate($invoice, $booking);

        return $invoice;
    }
}
