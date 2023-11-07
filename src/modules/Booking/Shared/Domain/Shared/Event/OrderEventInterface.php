<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

interface OrderEventInterface extends DomainEventInterface
{
    public function orderId(): int;
}
