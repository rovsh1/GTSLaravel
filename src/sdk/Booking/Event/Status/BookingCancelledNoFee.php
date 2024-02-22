<?php

namespace Sdk\Booking\Event\Status;

use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;

final class BookingCancelledNoFee extends AbstractStatusEvent implements InvoiceBecomeDeprecatedEventInterface
{
}
