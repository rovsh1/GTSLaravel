<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Command\Admin;

use Carbon\CarbonPeriod;
use Module\Booking\Common\Application\Command\Admin\CreateBooking as CreateEntityCommand;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Infrastructure\Models\BookingDetails;
use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\ValueObject\Percent;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateBookingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly SerializerInterface $serializer
    ) {}

    public function handle(CommandInterface|CreateBooking $command): int
    {
        return \DB::transaction(function () use ($command) {
            $bookingId = $this->commandBus->execute(
                new CreateEntityCommand(
                    clientId: $command->clientId,
                    creatorId: $command->creatorId,
                    orderId: $command->orderId,
                    note: $command->note,
                    type: BookingTypeEnum::HOTEL,
                )
            );

            $markupSettings = $this->hotelAdapter->getMarkupSettings($command->hotelId);
            $cancelConditions = $this->buildCancelConditionsByCancelPeriods(
                $markupSettings->cancelPeriods,
                $command->period
            );

            BookingDetails::create([
                'booking_id' => $bookingId,
                'hotel_id' => $command->hotelId,
                'date_start' => $command->period->getStartDate(),
                'date_end' => $command->period->getEndDate(),
                'cancel_conditions' => $this->serializer->serialize($cancelConditions)
            ]);

            return $bookingId;
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

        $maxDaysCount = null;
        $dailyMarkups = new DailyMarkupCollection();
        foreach ($availablePeriod->dailyMarkups as $dailyMarkup) {
            if ($dailyMarkup->daysCount > $maxDaysCount) {
                $maxDaysCount = $dailyMarkup->daysCount;
            }
            $dailyMarkups->add(
                new DailyMarkupOption(
                    new Percent($dailyMarkup->percent),
                    CancelPeriodTypeEnum::from($dailyMarkup->cancelPeriodType),
                    $dailyMarkup->daysCount
                )
            );
        }
        $cancelNoFeeDate = null;
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $bookingPeriod->getStartDate()->clone()->subDays($maxDaysCount)->toImmutable();
        }

        return new CancelConditions(
            new CancelMarkupOption(
                new Percent($availablePeriod->noCheckInMarkup->percent),
                CancelPeriodTypeEnum::from($availablePeriod->noCheckInMarkup->cancelPeriodType)
            ),
            $dailyMarkups,
            $cancelNoFeeDate
        );
    }
}
