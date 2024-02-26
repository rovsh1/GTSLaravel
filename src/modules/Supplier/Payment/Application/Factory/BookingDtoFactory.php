<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\Factory;

use Module\Supplier\Payment\Application\Dto\BookingDto;
use Module\Supplier\Payment\Domain\Booking\Booking;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Dto\CurrencyDto;
use Sdk\Shared\Dto\MoneyDto;

class BookingDtoFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function build(Booking $booking): BookingDto
    {
        $remainingAmount = $booking->supplierPrice()->value() - $booking->payedAmount()->value();

        $penalty = null;
        if ($booking->supplierPenalty() !== null) {
            $penalty = new MoneyDto(
                CurrencyDto::fromEnum($booking->supplierPrice()->currency(), $this->translator),
                $booking->supplierPenalty()->value(),
            );
            $remainingAmount = $booking->supplierPenalty()->value() - $booking->payedAmount()->value();
        }

        return new BookingDto(
            id: $booking->id()->value(),
            supplierId: $booking->supplierId()->value(),
            supplierPrice: new MoneyDto(
                CurrencyDto::fromEnum($booking->supplierPrice()->currency(), $this->translator),
                $booking->supplierPrice()->value(),
            ),
            supplierPenalty: $penalty,
            payedAmount: new MoneyDto(
                CurrencyDto::fromEnum($booking->payedAmount()->currency(), $this->translator),
                $booking->payedAmount()->value(),
            ),
            remainingAmount: new MoneyDto(
                CurrencyDto::fromEnum($booking->payedAmount()->currency(), $this->translator),
                $remainingAmount,
            ),
        );
    }

    /**
     * @param Booking[] $bookings
     * @return BookingDto[]
     */
    public function collection(array $bookings): array
    {
        return array_map(fn(Booking $booking) => $this->build($booking), $bookings);
    }
}
