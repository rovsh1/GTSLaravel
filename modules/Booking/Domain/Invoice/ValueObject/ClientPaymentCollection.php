<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\ValueObject;

use Module\Booking\Domain\Invoice\Entity\ClientPayment;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class ClientPaymentCollection extends AbstractValueObjectCollection
{
    public function totalSum(): float
    {
        $sum = 0.0;
        /** @var ClientPayment $item */
        foreach ($this->items as $item) {
            $sum += $item->paymentAmount()->sum();
        }

        return $sum;
    }

    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof ClientPayment) {
            throw new \InvalidArgumentException();
        }
    }
}
