<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class NotFoundServiceCancelConditions extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        ?string $message = 'Не заполнены условия отмены на период брони для услуги.',
        int $code = self::BOOKING_NOT_FOUND_SERVICE_CANCEL_CONDITIONS,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
