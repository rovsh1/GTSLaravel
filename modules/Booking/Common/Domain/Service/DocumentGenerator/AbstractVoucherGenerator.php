<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Entity\Voucher;

abstract class AbstractVoucherGenerator extends AbstractDocumentGenerator
{
    final public function generate(Voucher $voucher, BookingInterface $booking): void
    {
        $documentContent = (new TemplateBuilder($this->getTemplateName()))
            ->attributes(
                array_merge(
                    $this->getCompanyAttributes(),
                    $this->getReservationAttributes($booking),
                    $this->getVoucherAttributes($voucher),
                )
            )
            ->generate();

        $this->fileStorageAdapter->create(
            $voucher::class,
            $voucher->id()->value(),
            "voucher_{$voucher->id()->value()}.pdf",
            $documentContent
        );
    }

    abstract protected function getVoucherAttributes(Voucher $voucher): array;
}
