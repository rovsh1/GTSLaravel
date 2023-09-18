<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\ValueObject;

use Module\Booking\Domain\Invoice\Entity\SupplierPayment;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class SupplierPaymentCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof SupplierPayment) {
            throw new \InvalidArgumentException();
        }
    }
}