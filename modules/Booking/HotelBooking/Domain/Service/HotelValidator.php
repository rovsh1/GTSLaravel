<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service;

use Carbon\CarbonPeriod;
use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Exception\NotFoundHotelCancelPeriod;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\ResponseDto\MarkupSettings\CancelPeriodDto;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelValidator
{


    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter
    ) {}

    public function validateById(int $hotelId, CarbonPeriod $bookingPeriod): void
    {
        /** @var MarkupSettingsDto|null $hotelDto */
        $markupSettings = $this->hotelAdapter->getMarkupSettings($hotelId);
        if ($markupSettings === null) {
            throw new EntityNotFoundException("Hotel with id: {$hotelId} not found");
        }
        $this->validateByDto($markupSettings, $bookingPeriod);
    }

    public function validateByDto(MarkupSettingsDto $markupSettings, CarbonPeriod $bookingPeriod): void
    {
        //@todo накапливать массив ошибок и выкидывать в эксепшене
        /** @var CancelPeriodDto $availablePeriod */
        $availablePeriod = collect($markupSettings->cancelPeriods)->first(
            fn(mixed $cancelPeriod) => $bookingPeriod->overlaps($cancelPeriod->from, $cancelPeriod->to)
        );
        if ($availablePeriod === null) {
            throw new NotFoundHotelCancelPeriod('Not found cancel period for booking');
        }
        //@todo проверить цены, наценки, условия отмены, стандартные условия заезда/выезда,
    }

    public function isValid(int $hotelId, CarbonPeriod $bookingPeriod): bool
    {
        try {
            $this->validateById($hotelId, $bookingPeriod);
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}
