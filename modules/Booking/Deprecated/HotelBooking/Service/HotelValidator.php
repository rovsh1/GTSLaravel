<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\Service;

use Carbon\CarbonPeriod;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Exception\NotFoundHotelCancelPeriod;
use Module\Catalog\Application\Admin\Response\MarkupSettingsDto;
use Module\Catalog\Application\Admin\ResponseDto\MarkupSettings\CancelPeriodDto;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelValidator
{


    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter
    ) {}

    /**
     * @param int $hotelId
     * @param CarbonPeriod $bookingPeriod
     * @return void
     * @throws NotFoundHotelCancelPeriod
     */
    public function validateById(int $hotelId, CarbonPeriod $bookingPeriod): void
    {
        /** @var MarkupSettingsDto|null $hotelDto */
        $markupSettings = $this->hotelAdapter->getMarkupSettings($hotelId);
        if ($markupSettings === null) {
            throw new EntityNotFoundException("Hotel with id: {$hotelId} not found");
        }
        $this->validateByDto($markupSettings, $bookingPeriod);
    }

    /**
     * @param MarkupSettingsDto $markupSettings
     * @param CarbonPeriod $bookingPeriod
     * @return void
     * @throws NotFoundHotelCancelPeriod
     */
    public function validateByDto(MarkupSettingsDto $markupSettings, CarbonPeriod $bookingPeriod): void
    {
        //@todo накапливать массив ошибок и выкидывать в эксепшене
        /** @var CancelPeriodDto $availablePeriod */
        $availablePeriod = collect($markupSettings->cancelPeriods)->first(
            fn(mixed $cancelPeriod) => $bookingPeriod->overlaps($cancelPeriod->from, $cancelPeriod->to)
        );
        if ($availablePeriod === null) {
            throw new NotFoundHotelCancelPeriod();
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
