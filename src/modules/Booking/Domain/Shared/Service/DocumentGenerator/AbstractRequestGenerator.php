<?php

namespace Module\Booking\Domain\Shared\Service\DocumentGenerator;

use Module\Booking\Domain\BookingRequest\Service\TemplateBuilder;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Domain\Shared\Event\Contracts\BookingRequestableInterface;

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
