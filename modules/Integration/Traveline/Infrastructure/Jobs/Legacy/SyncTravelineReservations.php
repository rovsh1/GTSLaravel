<?php

namespace Module\Integration\Traveline\Infrastructure\Jobs\Legacy;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Module\Integration\Traveline\Application\Dto;
use Module\Integration\Traveline\Application\Dto\Reservation\CustomerDto;
use Module\Integration\Traveline\Application\Dto\Reservation\RoomDto;
use Module\Integration\Traveline\Application\Dto\ReservationDto;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Guest;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Hotel\Option;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Reservation;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\ReservationStatusEnum;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Room;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservation;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservationStatusEnum;
use Module\Integration\Traveline\Infrastructure\Models\TravelineHotel;

class SyncTravelineReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->addNewReservations();
        $this->updateExistsReservations();
    }

    private function addNewReservations(): void
    {
        $q = $this->getReservationQuery()
            ->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from("{$this->getTravelineReservationsTable()} as t")
                    ->whereColumn('t.reservation_id', 'reservation.id');
            });

        $this->createTravelineReservations($q->get());
    }

    private function updateExistsReservations(): void
    {
        $q = $this->getReservationQuery()
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from("{$this->getTravelineReservationsTable()} as t")
                    ->whereColumn('t.reservation_id', 'reservation.id');
            })
            ->whereNotNull('accepted_at')
            ->join($this->getTravelineReservationsTable(), function ($join) {
                $hotelReservationsTable = with(new Reservation)->getTable();
                $join->on("{$this->getTravelineReservationsTable()}.reservation_id", '=', "{$hotelReservationsTable}.id");
            })
            ->addSelect($this->getTravelineReservationsTable() . '.data as data');

        $q->chunk(100, function (Collection $collection) {
            $updateData = $collection->map(fn(Reservation $reservation) => $this->mapReservationsToTravelineUpdateData($reservation))
                ->filter()
                ->all();

            TravelineReservation::upsert($updateData, 'reservation_id', ['data', 'status', 'updated_at', 'accepted_at']);
        });
    }

    private function getReservationQuery(): Builder
    {
        return Reservation::query()
            ->whereExists(function ($query) {
                $travelineHotelsTable = with(new TravelineHotel)->getTable();
                $query->select(\DB::raw(1))
                    ->from("{$travelineHotelsTable} as t")
                    ->whereColumn('t.hotel_id', 'reservation.hotel_id');
            })
            ->withClient()
            ->with([
                'rooms',
                'rooms.guests',
                'rooms.checkInCondition',
                'rooms.checkOutCondition',
                'hotelDefaultCheckInStart',
                'hotelDefaultCheckOutEnd',
            ]);
    }

    private function mapReservationsToTravelineUpdateData(Reservation $reservation): ?array
    {
        $isCancelled = $this->isCancelledHotelReservationStatus($reservation->status);
        $travelineReservationStatus = $isCancelled ? TravelineReservationStatusEnum::Cancelled : TravelineReservationStatusEnum::Modified;

        $oldHash = md5($reservation->data);
        $newDto = $this->convertHotelReservationToDto($reservation, $travelineReservationStatus->value);
        $newHash = md5(json_encode($newDto));
        if ($oldHash === $newHash) {
            return null;
        }

        return [
            'reservation_id' => $reservation->id,
            'data' => json_encode($newDto),
            'status' => $travelineReservationStatus,
            'created_at' => $reservation->created,
            'updated_at' => now(),
            'accepted_at' => null,
        ];
    }

    private function getTravelineReservationsTable(): string
    {
        return with(new TravelineReservation)->getTable();
    }

    private function createTravelineReservations(Collection $hotelReservations): void
    {
        $preparedReservations = $hotelReservations->map(fn(Reservation $reservation) => [
            'reservation_id' => $reservation->id,
            'data' => json_encode($this->convertHotelReservationToDto($reservation, TravelineReservationStatusEnum::New->value)),
            'status' => TravelineReservationStatusEnum::New,
            'created_at' => $reservation->created,
            'updated_at' => $reservation->created,
        ])->all();

        TravelineReservation::insert($preparedReservations);
    }

    private function convertHotelReservationToDto(Reservation $reservation, string $status): ReservationDto
    {
        return ReservationDto::from([
                'number' => $reservation->id,
                'hotelId' => $reservation->hotel_id,
                'created' => $reservation->created,
                'arrivalTime' => $reservation->hotelDefaultCheckInStart?->value,
                'departureTime' => $reservation->hotelDefaultCheckOutEnd?->value,
                'rooms' => $this->convertHotelReservationsRoomsToDto(
                    $reservation->rooms,
                    $reservation->client_name,
                    new CarbonPeriod($reservation->date_checkin, $reservation->date_checkout),
                    $reservation->hotelDefaultCheckInStart,
                    $reservation->hotelDefaultCheckOutEnd,
                ),
                'status' => Dto\Reservation\StatusEnum::from($status),
                'currencyCode' => env('DEFAULT_CURRENCY_CODE'),
            ]
        );
    }

    /**
     * @param Collection $rooms
     * @param string $clientName
     * @param CarbonPeriod $period
     * @param Option|null $hotelDefaultCheckInStart
     * @param Option|null $hotelDefaultCheckOutEnd
     * @return RoomDto[]
     */
    private function convertHotelReservationsRoomsToDto(Collection $rooms, string $clientName, CarbonPeriod $period, ?Option $hotelDefaultCheckInStart, ?Option $hotelDefaultCheckOutEnd): array
    {
        return $rooms->map(function (Room $room) use ($clientName, $period, $hotelDefaultCheckInStart, $hotelDefaultCheckOutEnd) {
            return new RoomDto(
                $room->room_id,
                $room->rate_id,
                $this->covertRoomGuestsToDto($room->guests),
                $room->guests->count(),
                CustomerDto::from(['fullName' => $clientName]),
                $this->buildRoomPerDayPrices(
                    $period,
                    $room->price_net,
                    $room->checkInCondition,
                    $room->checkOutCondition,
                    $hotelDefaultCheckInStart,
                    $hotelDefaultCheckOutEnd
                ),
                Dto\Reservation\Room\TotalDto::from(['amountAfterTaxes' => $room->price_net]),
                $room->note
            );
        })->all();
    }

    /**
     * @param Collection $guests
     * @return \Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection
     */
    private function covertRoomGuestsToDto(Collection $guests)
    {
        $preparedGuests = $guests->map(fn(Guest $guest) => ['fullName' => $guest->fullname])->all();
        return Dto\Reservation\Room\GuestDto::collection($preparedGuests);
    }

    /**
     * @param CarbonPeriod $period
     * @param float $allDaysPrice
     * @param Room\CheckInOutConditions|null $roomCheckInCondition
     * @param Room\CheckInOutConditions|null $roomCheckOutCondition
     * @param Option|null $hotelDefaultCheckInStart
     * @param Option|null $hotelDefaultCheckOutEnd
     * @return Dto\Reservation\Room\DayPriceDto[]
     */
    private function buildRoomPerDayPrices(
        CarbonPeriod               $period,
        float                      $allDaysPrice,
        ?Room\CheckInOutConditions $roomCheckInCondition,
        ?Room\CheckInOutConditions $roomCheckOutCondition,
        ?Option                    $hotelDefaultCheckInStart,
        ?Option                    $hotelDefaultCheckOutEnd
    ): array
    {
        /**
         * @todo тут временно будет логика квотирования раннего/позднего заезда/выезда:
         *  - если ранний заезд - включаем день перед началом периода
         *  - если поздний заезд - включаем последний день периода
         */
        $preparedPeriod = $this->getPeriodByCheckInCondition($period, $hotelDefaultCheckInStart, $roomCheckInCondition);
        $preparedPeriod = $this->getPeriodByCheckOutCondition($preparedPeriod, $hotelDefaultCheckOutEnd, $roomCheckOutCondition);

        $countDays = $preparedPeriod->count();
        $dailyPrice = $allDaysPrice / $countDays;
        $prices = [];
        foreach ($preparedPeriod as $date) {
            $prices[] = new Dto\Reservation\Room\DayPriceDto($date->format('Y-m-d'), $dailyPrice);
        }
        return $prices;
    }

    private function getPeriodByCheckInCondition(CarbonPeriod $period, ?Option $hotelDefaultCheckInStart, ?Room\CheckInOutConditions $roomCheckInCondition): CarbonPeriod
    {
        if ($hotelDefaultCheckInStart === null) {
            //@todo что тут делать?
            \Log::warning('У отеля отсутствует дефолтное время заезда');
            return $period;
        }
        if ($roomCheckInCondition === null) {
            return $period;
        }
        $startDate = $period->getStartDate();
        $defaultCheckInTime = new Carbon($hotelDefaultCheckInStart->value);
        $expectedCheckInTime = new Carbon($roomCheckInCondition->start);
        if ($expectedCheckInTime < $defaultCheckInTime) {
            $startDate->subDay();
        }
        return new CarbonPeriod($startDate, $period->getEndDate());
    }

    private function getPeriodByCheckOutCondition(CarbonPeriod $period, ?Option $hotelDefaultCheckOutEnd, ?Room\CheckInOutConditions $roomCheckOutCondition): CarbonPeriod
    {
        if ($hotelDefaultCheckOutEnd === null) {
            //@todo что тут делать?
            \Log::warning('У отеля отсутствует дефолтное время выезда');
            return $period;
        }
        if ($roomCheckOutCondition === null) {
            return new CarbonPeriod($period->getStartDate(), $period->getEndDate()->subDay());
        }
        $endDate = $period->getEndDate();
        $defaultCheckInTime = new Carbon($hotelDefaultCheckOutEnd->value);
        $expectedCheckInTime = new Carbon($roomCheckOutCondition->end);
        if ($expectedCheckInTime > $defaultCheckInTime) {
            $endDate->addDay();
        }
        return new CarbonPeriod($period->getStartDate(), $endDate);
    }

    private function isCancelledHotelReservationStatus(ReservationStatusEnum $status): bool
    {
        return in_array($status, [
            ReservationStatusEnum::WaitingCancellation,
            ReservationStatusEnum::Cancelled,
            ReservationStatusEnum::CancelledFee,
            ReservationStatusEnum::CancelledNoFee,
            ReservationStatusEnum::RefundFee,
            ReservationStatusEnum::RefundNoFee,
        ]);
    }
}