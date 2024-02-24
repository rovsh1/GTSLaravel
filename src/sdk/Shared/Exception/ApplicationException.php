<?php

declare(strict_types=1);

namespace Sdk\Shared\Exception;

use ReflectionClass;
use Throwable;

abstract class ApplicationException extends \RuntimeException
{
    public const BOOKING_NOT_FOUND_HOTEL_CANCEL_PERIOD       = 1000;
    public const BOOKING_NOT_FOUND_ROOM_DATE_QUOTA           = 1001;
    public const BOOKING_CLOSED_ROOM_DATE_QUOTA              = 1002;
    public const BOOKING_NOT_ENOUGH_QUOTA                    = 1003;
    public const BOOKING_INVALID_ROOM_CLIENT_RESIDENCY       = 1004;
    public const BOOKING_ROOM_TOO_MANY_GUESTS                = 1005;
    public const BOOKING_HOTEL_DETAILS_EXPECTED              = 1006;
    public const BOOKING_SERVICE_PRICE_NOT_FOUND             = 1007;
    public const BOOKING_TRANSFER_SERVICE_DATE_UNDEFINED     = 1009;
    public const ORDER_HAS_BOOKING_IN_PROGRESS               = 1010;
    public const ORDER_INVOICE_NOT_FOUND                     = 1011;
    public const BOOKING_NOT_FOUND_SERVICE_CANCEL_CONDITIONS = 1012;
    public const ORDER_INVOICE_CREATING_FORBIDDEN            = 1013;
    public const ORDER_INVOICE_CANCELLATION_FORBIDDEN        = 1014;
    public const HOTEL_ROOM_NOT_FOUND                        = 1015;
    public const HOTEL_ROOM_PRICE_NOT_FOUND                  = 1016;
    public const LEND_ORDER_TO_PAYMENT_INSUFFICIENT_FUNDS    = 1017;
    public const LEND_ORDER_INVALID_SUM_DECIMALS             = 1018;
    public const ORDER_WITHOUT_BOOKINGS                      = 1019;
    public const BOOKING_CAR_BID_TOO_MANY_GUESTS             = 1020;
    public const BOOKING_SUPPLIER_PENALTY_CANNOT_BE_ZERO     = 1021;
    public const BOOKING_PERIOD_DATES_CANNOT_BE_EQUAL        = 1022;
    public const ORDER_HAS_NOT_CANCELLED_BOOKING             = 1023;
    public const LEND_BOOKING_TO_PAYMENT_INSUFFICIENT_FUNDS  = 1024;
    public const LEND_BOOKING_INVALID_SUM_DECIMALS           = 1025;

    private static array $errorsArray;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            '',
            $this->getErrorCode(),
            $previous
        );

        $this->message = $this->makeErrorMessage();
    }

    final public static function getErrorsArray(): array
    {
        if (isset(self::$errorsArray)) {
            return self::$errorsArray;
        }

        $reflection = new ReflectionClass(self::class);

        return self::$errorsArray = $reflection->getConstants();
    }

    final public function getErrorKey(): string
    {
        return array_search($this->getCode(), self::getErrorsArray());
    }

    protected function getErrorParameters(): array
    {
        return [];
    }

    private function getErrorCode(): int
    {
        return $this->code
            ?? throw new \LogicException('Application exception [' . static::class . '] code not implemented');
    }

    private function makeErrorMessage(): string
    {
        return __('Exception::' . $this->getErrorKey(), $this->getErrorParameters());
    }
}
