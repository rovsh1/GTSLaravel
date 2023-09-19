<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Event\Contracts\BookingRequestableInterface;

abstract class AbstractRequestGenerator extends AbstractDocumentGenerator implements RequestGeneratorInterface
{
    final public function generate(BookingRequestableInterface|AbstractBooking $booking): string
    {
        return (new TemplateBuilder($this->getTemplateName()))
            ->attributes(
                array_merge(
                    $this->getCompanyAttributes(),
                    $this->getBookingAttributes($booking),
                )
            )
            ->generate();
    }
}
