<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;

abstract class AbstractRequestGenerator extends AbstractDocumentGenerator implements RequestGeneratorInterface
{
    final public function generate(Request $request, BookingRequestableInterface|AbstractBooking $booking): void
    {
        $documentContent = $this->getTemplateBuilder()
            ->attributes(
                array_merge(
                    $this->getCompanyAttributes(),
                    $this->getReservationAttributes($booking),
                )
            )
            ->generate();

        $this->fileStorageAdapter->create(
            $request::class,
            $request->id()->value(),
            "{$request->getFilename()}.pdf",
            $documentContent
        );
    }
}
