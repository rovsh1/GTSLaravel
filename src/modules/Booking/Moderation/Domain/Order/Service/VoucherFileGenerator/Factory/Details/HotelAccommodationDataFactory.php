<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\Details;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\Service\DetailOptionDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\Service\PriceDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\ServiceInfoDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\BookingPeriodDataFactory;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\CancelConditionsDataFactory;
use Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Factory\GuestDataFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Hotel\Moderation\Application\Dto\ContactDto;
use Module\Hotel\Moderation\Application\Dto\HotelDto;
use Module\Hotel\Moderation\Application\Dto\PriceRateDto;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\HotelBooking\Condition;
use Sdk\Shared\Enum\ContactTypeEnum;
use Sdk\Shared\Enum\CurrencyEnum;

class HotelAccommodationDataFactory
{
    /**
     * @var array<int, HotelDto> $hotels
     */
    private array $hotels = [];

    /**
     * @var array<int, array<int, PriceRateDto>> $hotelPriceRates
     */
    private array $hotelPriceRates = [];

    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingPeriodDataFactory $bookingPeriodDataFactory,
        private readonly GuestDataFactory $guestDataFactory,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly CancelConditionsDataFactory $cancelConditionsDataFactory,
    ) {}

    public function build(Booking $booking, HotelAccommodation $accommodation): ServiceInfoDto
    {
        /** @var HotelBooking $details */
        $details = $this->detailsRepository->findOrFail($booking->id());
        $serviceTitle = "Отель: {$details->hotelInfo()->name()}";

        return new ServiceInfoDto(
            title: $serviceTitle,
            bookingPeriod: $this->bookingPeriodDataFactory->build($details),
            detailOptions: $this->buildDetails($details, $accommodation),
            guests: $this->guestDataFactory->build($accommodation->guestIds()),
            price: $this->buildPrice($booking->prices()->clientPrice()->currency(), $accommodation),
            externalNumber: $details->externalNumber()?->number(),
            cancelConditions: $this->cancelConditionsDataFactory->build($booking->cancelConditions()),
            status: $booking->status()->value()->name,//@todo статус
        );
    }

    private function getHotelPriceRate(int $hotelId, int $rateId): ?PriceRateDto
    {
        if (!array_key_exists($hotelId, $this->hotelPriceRates)) {
            $hotelPriceRates = $this->hotelAdapter->getHotelRates($hotelId);
            $this->hotelPriceRates[$hotelId] = collect($hotelPriceRates)->keyBy('id')->all();
        }

        return $this->hotelPriceRates[$hotelId][$rateId] ?? null;
    }

    private function getHotel(int $hotelId): HotelDto
    {
        if (!array_key_exists($hotelId, $this->hotels)) {
            $this->hotels[$hotelId] = $this->hotelAdapter->findById($hotelId);
        }

        return $this->hotels[$hotelId];
    }

    private function getHotelAddress(int $hotelId): ?string
    {
        return $this->getHotel($hotelId)->address;
    }

    private function getHotelPhone(int $hotelId): ?string
    {
        $contacts = $this->getHotel($hotelId)->contacts;
        /** @var ContactDto|null $phone */
        $phone = Arr::first($contacts, fn(ContactDto $contact) => $contact->type === ContactTypeEnum::PHONE->value);

        return $phone?->value;
    }

    private function buildDetails(HotelBooking $details, HotelAccommodation $accommodation): Collection
    {
        $checkInTime = $accommodation->details()->earlyCheckIn() !== null
            ? $this->buildConditionDetailOption($accommodation->details()->earlyCheckIn())
            : "с {$details->hotelInfo()->checkInTime()->value()}";

        $checkOutTime = $accommodation->details()->lateCheckOut() !== null
            ? $this->buildConditionDetailOption($accommodation->details()->lateCheckOut())
            : "до {$details->hotelInfo()->checkOutTime()->value()}";

        $hotelId = $details->hotelInfo()->id();
        $priceRate = $this->getHotelPriceRate($hotelId, $accommodation->details()->rateId());

        return collect([
            DetailOptionDto::createText('Адрес', $this->getHotelAddress($hotelId)),
            DetailOptionDto::createText('Телефон', $this->getHotelPhone($hotelId)),
            DetailOptionDto::createDate('Дата заезда', $details->bookingPeriod()->dateFrom()),
            DetailOptionDto::createText('Время заезда', $checkInTime),
            DetailOptionDto::createDate('Дата выезда', $details->bookingPeriod()->dateTo()),
            DetailOptionDto::createText('Время выезда', $checkOutTime),
            DetailOptionDto::createNumber('Ночей', $details->bookingPeriod()->nightsCount()),
            DetailOptionDto::createText('Номер', $accommodation->roomInfo()->name()),
            DetailOptionDto::createText('Питание', $priceRate?->name),
            DetailOptionDto::createText('Примечание', $accommodation->details()->guestNote()),
        ]);
    }

    private function buildConditionDetailOption(Condition $condition): string
    {
        $timePeriod = "с {$condition->timePeriod()->from()} по {$condition->timePeriod()->to()}";
        $markup = "(+{$condition->priceMarkup()->value()}%)";

        return "{$timePeriod} {$markup}";
    }

    private function buildPrice(CurrencyEnum $currency, HotelAccommodation $accommodation): PriceDto
    {
        $clientPrice = $accommodation->prices()->clientPrice();
        $amount = $clientPrice->manualValue() ?? $clientPrice->value();

        return new PriceDto(
            1,
            $amount,
            $amount,
            $currency->name,
            null,//@todo штраф за номер?
        );
    }
}
