<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Supplier;

use Module\Supplier\Payment\Application\RequestDto\LendBookingToPaymentRequestDto;
use Module\Supplier\Payment\Application\UseCase\BookingsLandingToPayment;
use Module\Supplier\Payment\Application\UseCase\GetPaymentBookings;
use Module\Supplier\Payment\Application\UseCase\GetWaitingPaymentBookings;

class BookingAdapter
{
    public function getWaitingPaymentBookings(int $paymentId): array
    {
        return app(GetWaitingPaymentBookings::class)->execute($paymentId);
    }

    public function getPaymentBookings(int $paymentId): array
    {
        return app(GetPaymentBookings::class)->execute($paymentId);
    }

    public function lendBookings(int $paymentId, array $bookings): void
    {
        $bookingsDto = array_map(fn(array $data) => new LendBookingToPaymentRequestDto(
            $data['id'],
            $data['sum'],
        ), $bookings);

        app(BookingsLandingToPayment::class)->execute($paymentId, $bookingsDto);
    }
}
