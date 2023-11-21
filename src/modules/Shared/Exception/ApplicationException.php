<?php

declare(strict_types=1);

namespace Module\Shared\Exception;

class ApplicationException extends \RuntimeException
{
    public const UNKNOWN = 0;

    public const BOOKING_NOT_FOUND_HOTEL_CANCEL_PERIOD = 1000;
    public const BOOKING_NOT_FOUND_ROOM_DATE_QUOTA = 1001;
    public const BOOKING_CLOSED_ROOM_DATE_QUOTA = 1002;
    public const BOOKING_NOT_ENOUGH_QUOTA = 1003;
    public const BOOKING_INVALID_ROOM_CLIENT_RESIDENCY = 1004;
    public const BOOKING_ROOM_TOO_MANY_GUESTS = 1005;
    public const BOOKING_HOTEL_ROOM_PRICE_NOT_FOUND = 1006;
    public const BOOKING_AIRPORT_SERVICE_PRICE_NOT_FOUND = 1007;
    public const BOOKING_TRANSFER_SERVICE_PRICE_NOT_FOUND = 1008;
    public const BOOKING_TRANSFER_SERVICE_DATE_UNDEFINED = 1009;
    public const ORDER_HAS_BOOKING_IN_PROGRESS = 1010;
    public const BOOKING_NOT_FOUND_SERVICE_CANCEL_CONDITIONS = 1011;
    public const INVALID_ORDER_STATUS_TO_CREATE_INVOICE = 1012;
    public const INVALID_ORDER_STATUS_TO_CANCEL_INVOICE = 1013;

    private const DEFAULT_MESSAGE = 'Неизвестная ошибка. Пожалуйста, обратитесь в техническую поддержку.';

    public function __construct(
        ?string $message = null,
        int $code = self::UNKNOWN,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message ?? self::DEFAULT_MESSAGE, $code, $previous);
    }
}
