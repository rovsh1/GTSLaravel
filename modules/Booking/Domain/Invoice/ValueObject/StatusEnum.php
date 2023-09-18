<?php

namespace Module\Booking\Domain\Invoice\ValueObject;

enum StatusEnum: int
{
    case NOT_PAID = 1;
    case PARTIAL_PAID = 2;
    case PAID = 3;

    public static function fromSumCompare(float $paidSum, float $totalSum): StatusEnum
    {
        return match (true) {
            $paidSum === 0.0 => StatusEnum::NOT_PAID,
            $paidSum >= $totalSum => StatusEnum::PARTIAL_PAID,
            default => StatusEnum::PAID
        };
    }
}
