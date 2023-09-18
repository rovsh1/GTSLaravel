<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\ValueObject;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class InvoiceAmountCollection extends AbstractValueObjectCollection
{
    public function amountSum(): float
    {
        $sum = 0.0;
        /** @var InvoiceAmount $item */
        foreach ($this->items as $item) {
            $sum += $item->amountSum();
        }

        return $sum;
    }

    public function paidSum(): float
    {
        $sum = 0.0;
        /** @var InvoiceAmount $item */
        foreach ($this->items as $item) {
            $sum += $item->paidSum();
        }

        return $sum;
    }

    public function updatePaid(BookingId $bookingId, float $paidSum): InvoiceAmountCollection
    {
        $items = [];
        /** @var InvoiceAmount $item */
        foreach ($this->items as $item) {
            if ($bookingId->isEqual($item->bookingId())) {
                $items[] = new InvoiceAmount(
                    bookingId: $bookingId,
                    amountSum: $item->amountSum(),
                    paidSum: $paidSum
                );
            } else {
                $items[] = $item;
            }
        }

        return new InvoiceAmountCollection($items);
    }

    public function updateAmount(BookingId $bookingId, float $amountSum): InvoiceAmountCollection
    {
        $items = [];
        /** @var InvoiceAmount $item */
        foreach ($this->items as $item) {
            if ($bookingId->isEqual($item->bookingId())) {
                $items[] = new InvoiceAmount(
                    bookingId: $bookingId,
                    amountSum: $amountSum,
                    paidSum: $item->paidSum()
                );
            } else {
                $items[] = $item;
            }
        }

        return new InvoiceAmountCollection($items);
    }

    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof InvoiceAmount) {
            throw new \InvalidArgumentException();
        }
    }
}