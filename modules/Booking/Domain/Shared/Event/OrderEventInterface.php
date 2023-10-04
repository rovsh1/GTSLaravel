<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

interface OrderEventInterface extends DomainEventInterface
{
    public function orderId(): int;
}