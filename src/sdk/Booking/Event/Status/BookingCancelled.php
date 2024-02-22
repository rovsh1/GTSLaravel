<?php

namespace Sdk\Booking\Event\Status;

use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;

final class BookingCancelled extends AbstractStatusEvent implements InvoiceBecomeDeprecatedEventInterface
{
}
