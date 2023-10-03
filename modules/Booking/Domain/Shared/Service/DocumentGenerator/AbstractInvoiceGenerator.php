<?php

namespace Module\Booking\Domain\Shared\Service\DocumentGenerator;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Entity\Invoice;

abstract class AbstractInvoiceGenerator extends AbstractDocumentGenerator
{
    final public function generate(Invoice $invoice, BookingInterface $booking): void
    {
        $documentContent = (new TemplateBuilder($this->getTemplateName()))
            ->attributes(
                array_merge(
                    $this->getCompanyAttributes(),
                    $this->getBookingAttributes($booking),
                    $this->getInvoiceAttributes($invoice),
                )
            )
            ->generate();

        $this->fileStorageAdapter->create(
            $invoice::class,
            $invoice->id()->value(),
            "invoice_{$invoice->id()->value()}.pdf",
            $documentContent
        );
    }

    abstract protected function getInvoiceAttributes(Invoice $invoice): array;
}
