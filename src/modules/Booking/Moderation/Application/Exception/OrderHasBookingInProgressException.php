<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class OrderHasBookingInProgressException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::ORDER_HAS_BOOKING_IN_PROGRESS,
        ?string $message = 'У заказа есть брони в активных статусах. Брони должны быть подтверждены или отменены.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
