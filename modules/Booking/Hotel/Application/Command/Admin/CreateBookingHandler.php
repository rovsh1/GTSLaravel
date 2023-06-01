<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Command\Admin;

use Carbon\CarbonPeriod;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\Hotel\Domain\Adapter\OrderAdapterInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Infrastructure\Models\Booking;
use Module\Booking\Hotel\Infrastructure\Models\BookingDetails;
use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\ValueObject\Percent;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateBookingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly OrderAdapterInterface $orderAdapter,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly SerializerInterface $serializer
    ) {}

    public function handle(CommandInterface|CreateBooking $command): int
    {
        return \DB::transaction(function () use ($command) {
            $orderId = $command->orderId;
            if ($orderId === null) {
                $orderId = $this->orderAdapter->createOrder($command->clientId);
            }

            $booking = Booking::create([
                'order_id' => $orderId,
                'source' => 1, //@todo hack источник создания брони
                'status' => BookingStatusEnum::CREATED,
                'note' => $command->note,
                'creator_id' => $command->creatorId,
            ]);

            $markupSettings = $this->hotelAdapter->getMarkupSettings($command->hotelId);
            $cancelConditions = $this->buildCancelConditionsByCancelPeriods(
                $markupSettings->cancelPeriods,
                $command->period
            );
            //@todo из квот release_days, до даты booking_start_date - release_days (бесплатно)

            //@todo чтобы получить release_days нужен ID номера, квоты без номеров не существуют
            BookingDetails::create([
                'booking_id' => $booking->id,
                'hotel_id' => $command->hotelId,
                'date_start' => $command->period->getStartDate(),
                'date_end' => $command->period->getEndDate(),
                'cancel_conditions' => $this->serializer->serialize($cancelConditions)
            ]);

            return $booking->id;
        });
    }

    private function buildCancelConditionsByCancelPeriods(
        array $cancelPeriods,
        CarbonPeriod $bookingPeriod
    ): CancelConditions {
        $availablePeriod = collect($cancelPeriods)->first(
            fn(mixed $cancelPeriod) => $bookingPeriod->overlaps($cancelPeriod->from, $cancelPeriod->to)
        );
        if ($availablePeriod === null) {
            //@todo понять что тут делать
            throw new \Exception('Not found cancel period for booking');
        }

        $dailyMarkups = new DailyMarkupCollection();
        foreach ($availablePeriod->dailyMarkups as $dailyMarkup) {
            $dailyMarkups->add(
                new DailyMarkupOption(
                    new Percent($dailyMarkup->percent),
                    CancelPeriodTypeEnum::from($dailyMarkup->cancelPeriodType),
                    $dailyMarkup->daysCount
                )
            );
        }
        return new CancelConditions(
            new CancelMarkupOption(
                new Percent($availablePeriod->noCheckInMarkup->percent),
                CancelPeriodTypeEnum::from($availablePeriod->noCheckInMarkup->cancelPeriodType)
            ),
            $dailyMarkups,
        );
    }
}
