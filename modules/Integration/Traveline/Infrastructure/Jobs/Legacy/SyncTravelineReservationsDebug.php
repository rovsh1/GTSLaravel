<?php

namespace Module\Integration\Traveline\Infrastructure\Jobs\Legacy;

use Carbon\CarbonInterface;
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

class SyncTravelineReservationsDebug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(int $bookingId)
    {
        $this->addNewReservations();
        $this->updateExistsReservations($bookingId);
    }

    private function addNewReservations(): void
    {
//        $q = $this->getReservationQuery()
//            ->withoutSoftDeleted()
//            //Processing - созданная чрез админку. Paid - создана через сайт и оплачена картой. Confirmed - создана через сайт и оплата по договору. WaitingProcessing - Изменена через админку.
//            ->whereIn('reservation.status', [ReservationStatusEnum::Processing, ReservationStatusEnum::Paid, ReservationStatusEnum::Confirmed, ReservationStatusEnum::WaitingProcessing])
//            ->whereNotExists(function ($query) {
//                $query->selectRaw(1)
//                    ->from("{$this->getTravelineReservationsTable()} as t")
//                    ->whereColumn('t.reservation_id', 'reservation.id');
//            });
//
//        $this->createTravelineReservations($q->get());
    }

    private function updateExistsReservations(int $bookingId): void
    {
        $q = $this->getReservationQuery()
            ->where('reservation.id', $bookingId)
            ->join($this->getTravelineReservationsTable(), function ($join) {
                $hotelReservationsTable = with(new Reservation)->getTable();
                $join->on(
                    "{$this->getTravelineReservationsTable()}.reservation_id",
                    '=',
                    "{$hotelReservationsTable}.id"
                );
            })
            ->addSelect($this->getTravelineReservationsTable() . '.data as data');

        $q->chunk(100, function (Collection $collection) {
            $updateData = $collection->map(
                fn(Reservation $reservation) => $this->mapReservationsToTravelineUpdateData($reservation)
            )
                ->filter()
                ->all();
//            TravelineReservation::upsert($updateData, 'reservation_id', ['data', 'status', 'updated_at', 'accepted_at']);
        });
    }

    private function getReservationQuery(): Builder
    {
        return Reservation::query()
            ->whereExists(function ($query) {
                $travelineHotelsTable = with(new TravelineHotel)->getTable();
                $query->selectRaw(1)
                    ->from("{$travelineHotelsTable} as t")
                    ->whereColumn('t.hotel_id', 'reservation.hotel_id')
                    ->whereColumn('reservation.created', '>=', 't.created_at');
            })
            ->withClient()
            ->whereQuoteType()
            ->withManagerEmail()
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
        $isCancelled = $reservation->deletion_mark || $this->isCancelledHotelReservationStatus($reservation->status);
        $travelineReservationStatus = $isCancelled ? TravelineReservationStatusEnum::Cancelled : TravelineReservationStatusEnum::Modified;

        $oldHash = md5($reservation->data);
        $newDto = $this->convertHotelReservationToDto($reservation, $travelineReservationStatus->value);
        dd($newDto);
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
        $preparedReservations = $hotelReservations->map(function (Reservation $reservation) {
            $reservationDto = $this->convertHotelReservationToDto(
                $reservation,
                TravelineReservationStatusEnum::New->value
            );
            if ($reservationDto->roomStays->count() === 0) {
                return null;
            }

            return [
                'reservation_id' => $reservation->id,
                'data' => json_encode($reservationDto),
                'status' => TravelineReservationStatusEnum::New,
                'created_at' => $reservation->created,
                'updated_at' => $reservation->created,
            ];
        })->filter()->all();

        TravelineReservation::insert($preparedReservations);
    }

    private function convertHotelReservationToDto(Reservation $reservation, string $status): ReservationDto
    {
        $period = new CarbonPeriod($reservation->date_checkin, $reservation->date_checkout);

        return ReservationDto::from([
                'number' => $reservation->id,
                'hotelId' => $reservation->hotel_id,
                'created' => $reservation->created,
                'arrivalTime' => $reservation->hotelDefaultCheckInStart?->value,
                'departureTime' => $reservation->hotelDefaultCheckOutEnd?->value,
                'rooms' => $this->convertHotelReservationsRoomsToDto(
                    $reservation->rooms,
                    $period,
                    $reservation->hotelDefaultCheckInStart,
                    $reservation->hotelDefaultCheckOutEnd,
                ),
                'status' => Dto\Reservation\StatusEnum::from($status),
                'currencyCode' => env('DEFAULT_CURRENCY_CODE'),
                'additionalInfo' => $this->buildAdditionalInfo($period, $reservation->rooms),
                'customer' => $this->getDefaultCustomer($reservation->manager_email),
            ]
        );
    }

    private function getFullNameParts(string $fullName): array
    {
        $nameParts = explode(' ', $fullName);
        $middleName = null;

        if (count($nameParts) === 3) {
            $middleName = $nameParts[2];
        }

        return [
            $nameParts[0],
            $nameParts[1] ?? null,
            $middleName
        ];
    }

    private function getDefaultCustomer(?string $managerEmail): CustomerDto
    {
        return new CustomerDto(
            'GoToStans',
            null,
            null,
            $managerEmail ?? 'info@gotostans.com',
            '+998 78 120-90-12',
        );
    }

    /**
     * @param Collection $rooms
     * @param CarbonPeriod $period
     * @param Option|null $hotelDefaultCheckInStart
     * @param Option|null $hotelDefaultCheckOutEnd
     * @return RoomDto[]
     */
    private function convertHotelReservationsRoomsToDto(
        Collection $rooms,
        CarbonPeriod $period,
        ?Option $hotelDefaultCheckInStart,
        ?Option $hotelDefaultCheckOutEnd
    ): array {
        //в старом интерфейсе можно указать кол-во комнат, и не расселять гостей по ним. Для таких кейсов создаю отдельные комнаты в тревелайн.
        $fakeRooms = [];

        return $rooms->map(
            function (Room $room) use ($period, $hotelDefaultCheckInStart, $hotelDefaultCheckOutEnd, &$fakeRooms) {
                if ($room->guests()->count() === 0) {
                    return null;
                }
                $guestsDto = $this->covertRoomGuestsToDto($room->guests);
                $guestChunks = array_chunk($guestsDto->all(), $room->guests_number);
                $countChunks = count($guestChunks);
                $preparedPrice = $room->price_net;
                if ($countChunks > 1) {
                    $preparedPrice = $room->price_net / $countChunks;
                }
                while (count($guestChunks) > 1) {
                    $guests = array_shift($guestChunks);
                    $fakeRooms[] = new RoomDto(
                        $room->room_id,
                        $room->rate_id,
                        Dto\Reservation\Room\GuestDto::collection($guests),
                        count($guests),
                        $this->buildRoomPerDayPrices(
                            $period,
                            $preparedPrice,
                            $room->checkInCondition,
                            $room->checkOutCondition,
                            $hotelDefaultCheckInStart,
                            $hotelDefaultCheckOutEnd
                        ),
                        Dto\Reservation\Room\TotalDto::from(['amountAfterTaxes' => $preparedPrice]),
                        $room->note,
                    );
                }

                $guests = array_shift($guestChunks);

                return new RoomDto(
                    $room->room_id,
                    $room->rate_id,
                    Dto\Reservation\Room\GuestDto::collection($guests),
                    count($guests),
                    $this->buildRoomPerDayPrices(
                        $period,
                        $preparedPrice,
                        $room->checkInCondition,
                        $room->checkOutCondition,
                        $hotelDefaultCheckInStart,
                        $hotelDefaultCheckOutEnd
                    ),
                    Dto\Reservation\Room\TotalDto::from(['amountAfterTaxes' => $preparedPrice]),
                    $room->note,
                );
            }
        )->filter()->merge($fakeRooms)->all();
    }

    /**
     * @param CarbonPeriod $period
     * @param Collection<int, Room>|Room[] $rooms
     * @return string|null
     */
    private function buildAdditionalInfo(CarbonPeriod $period, Collection $rooms): ?string
    {
        $roomsAdditionalInfo = $rooms->map(
            fn(Room $room) => $this->buildRoomAdditionalInfo(
                $room->room_name,
                $period,
                $room->checkInCondition,
                $room->checkOutCondition
            )
        )->filter();

        return $roomsAdditionalInfo->isEmpty() ? null : $roomsAdditionalInfo->implode("\n");
    }

    private function buildRoomAdditionalInfo(
        string $roomName,
        CarbonPeriod $period,
        ?Room\CheckInOutConditions $roomCheckInCondition,
        ?Room\CheckInOutConditions $roomCheckOutCondition
    ): ?string {
        $comment = '';
        if ($roomCheckInCondition !== null) {
            $checkInDate = $period->getStartDate()->format('d.m.Y');
            $comment .= "Фактическое время заезда (ранний заезд) с {$roomCheckInCondition->start} {$checkInDate}. ";
        }
        if ($roomCheckOutCondition !== null) {
            $checkOutDate = $period->getEndDate()->format('d.m.Y');
            $comment .= "Фактическое время выезда (поздний выезд) до {$roomCheckOutCondition->end} {$checkOutDate}";
        }

        return empty($comment) ? null : $roomName . ': ' . $comment;
    }

    /**
     * @param Collection $guests
     * @return \Spatie\LaravelData\CursorPaginatedDataCollection|\Spatie\LaravelData\DataCollection|\Spatie\LaravelData\PaginatedDataCollection
     */
    private function covertRoomGuestsToDto(Collection $guests)
    {
        $preparedGuests = $guests->map(function (Guest $guest) {
            [$name, $lastName, $middleName] = $this->getFullNameParts($guest->fullname);

            return [
                'firstName' => $name,
                'lastName' => $lastName,
                'middleName' => $middleName
            ];
        })->all();

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
        CarbonPeriod $period,
        float $allDaysPrice,
        ?Room\CheckInOutConditions $roomCheckInCondition,
        ?Room\CheckInOutConditions $roomCheckOutCondition,
        ?Option $hotelDefaultCheckInStart,
        ?Option $hotelDefaultCheckOutEnd
    ): array {
        /**
         * @todo тут временно будет логика квотирования раннего/позднего заезда/выезда:
         *  - если ранний заезд - включаем день перед началом периода
         *  - если поздний заезд - включаем последний день периода
         */
        $startDate = $this->getPeriodStartDateByCheckInCondition(
            $period,
            $hotelDefaultCheckInStart,
            $roomCheckInCondition
        );
        $endDate = $this->getPeriodEndDateByCheckOutCondition(
            $period,
            $hotelDefaultCheckOutEnd,
            $roomCheckOutCondition
        );
        $preparedPeriod = new CarbonPeriod($startDate, $endDate, 'P1D');

        $countDays = $preparedPeriod->count();
        $dailyPrice = $allDaysPrice / $countDays;
        $prices = [];
        foreach ($preparedPeriod as $date) {
            $prices[] = new Dto\Reservation\Room\DayPriceDto($date->format('Y-m-d'), $dailyPrice);
        }

        return $prices;
    }

    private function getPeriodStartDateByCheckInCondition(
        CarbonPeriod $period,
        ?Option $hotelDefaultCheckInStart,
        ?Room\CheckInOutConditions $roomCheckInCondition
    ): CarbonInterface {
        if ($hotelDefaultCheckInStart === null) {
            //@todo что тут делать?
            \Log::warning('У отеля отсутствует дефолтное время заезда');

            return $period->getStartDate();
        }
        if ($roomCheckInCondition === null) {
            return $period->getStartDate();
        }
        $startDate = $period->getStartDate()->clone();
        if ($roomCheckInCondition->start < $hotelDefaultCheckInStart->value) {
            $startDate->subDay();
        }

        return $startDate;
    }

    private function getPeriodEndDateByCheckOutCondition(
        CarbonPeriod $period,
        ?Option $hotelDefaultCheckOutEnd,
        ?Room\CheckInOutConditions $roomCheckOutCondition
    ): CarbonInterface {
        //кол-во ночей считается на 1 день меньше периода
        $endDate = $period->getEndDate()->clone()->subDay();
        if ($hotelDefaultCheckOutEnd === null) {
            //@todo что тут делать?
            \Log::warning('У отеля отсутствует дефолтное время выезда');

            return $endDate;
        }
        if ($roomCheckOutCondition === null) {
            return $endDate;
        }
        if ($roomCheckOutCondition->end > $hotelDefaultCheckOutEnd->value) {
            $endDate->addDay();
        }

        return $endDate;
    }

    private function isCancelledHotelReservationStatus(ReservationStatusEnum $status): bool
    {
        return in_array($status, [
            ReservationStatusEnum::Cancelled,
            ReservationStatusEnum::CancelledFee,
            ReservationStatusEnum::CancelledNoFee,
            ReservationStatusEnum::RefundFee,
            ReservationStatusEnum::RefundNoFee,
        ]);
    }
}
