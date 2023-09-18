<?php

declare(strict_types=1);

namespace Module\Shared\Application\Exception;

class ApplicationException extends \RuntimeException
{
    public const UNKNOWN = 0;
    //booking errors
    public const BOOKING_NOT_FOUND_HOTEL_CANCEL_PERIOD = 1000;
    public const BOOKING_NOT_FOUND_ROOM_DATE_QUOTA = 1001;
    public const BOOKING_CLOSED_ROOM_DATE_QUOTA = 1002;
    public const BOOKING_NOT_ENOUGH_QUOTA = 1003;
    public const BOOKING_INVALID_ROOM_CLIENT_RESIDENCY = 1004;
    public const BOOKING_ROOM_TOO_MANY_GUESTS = 1005;

    private const DEFAULT_MESSAGE = 'Неизвестная ошибка. Пожалуйста, обратитесь в техническую поддержку.';

    public function __construct(
        ?string $message = null,
        int $code = self::UNKNOWN,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message ?? self::DEFAULT_MESSAGE, $code, $previous);
    }
}
