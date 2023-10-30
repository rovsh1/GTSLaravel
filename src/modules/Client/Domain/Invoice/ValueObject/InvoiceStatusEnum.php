<?php

namespace Module\Client\Domain\Invoice\ValueObject;

enum InvoiceStatusEnum: int
{
    case NOT_PAID = 1;
    case PARTIAL_PAID = 2;
    case PAID = 3;
    case DELETED = 4;

    public static function fromSumCompare(float $paidSum, float $totalSum): InvoiceStatusEnum
    {
        return match (true) {
            $paidSum === 0.0 => InvoiceStatusEnum::NOT_PAID,
            $paidSum >= $totalSum => InvoiceStatusEnum::PARTIAL_PAID,
            default => InvoiceStatusEnum::PAID
        };
    }
}
