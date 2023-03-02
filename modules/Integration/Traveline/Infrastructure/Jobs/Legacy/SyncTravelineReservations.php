<?php

namespace Module\Integration\Traveline\Infrastructure\Jobs\Legacy;

use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Module\Integration\Traveline\Application\Dto;
use Module\Integration\Traveline\Application\Dto\Reservation\CustomerDto;
use Module\Integration\Traveline\Application\Dto\Reservation\RoomDto;
use Module\Integration\Traveline\Application\Dto\ReservationDto;
use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Guest;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Reservation;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\ReservationStatusEnum;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\Room;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservation;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservationStatusEnum;

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
    public function handle(HotelRepositoryInterface $hotelRepository)
    {
        $hotelIds = $hotelRepository->getIntegratedHotelIds();
        $reservations = [];
        foreach (array_chunk($hotelIds, 100) as $hotelIds) {
            $reservations[] = Reservation::whereIn('hotel_id', $hotelIds)
                ->withClient()
                ->with([
                    'rooms',
                    'rooms.guests',
                    'rooms.checkInCondition',
                    'rooms.checkOutCondition',
                    'hotelDefaultCheckInStart',
                    'hotelDefaultCheckOutEnd',
                ])
                ->get()
                ->all();
        }
        $reservations = collect(array_merge(...$reservations))->keyBy('id');

        $hotelReservationIds = $reservations->keys()->toArray();
        $existReservationIds = TravelineReservation::query()
            ->select('reservation_id')
            ->get()
            ->pluck('reservation_id')
            ->toArray();
        $toCreateReservationIds = array_diff($hotelReservationIds, $existReservationIds);
        $toCreateReservations = $reservations->only($toCreateReservationIds);
        $this->createTravelineReservations($toCreateReservations);

        $travelineReservations = TravelineReservation::query()
            ->whereNotIn('reservation_id', $toCreateReservationIds)
            ->get()
            ->keyBy('reservation_id');
        $reservations->except($toCreateReservationIds)
            ->map(function (Reservation $reservation) use ($travelineReservations) {
                /** @var TravelineReservation $travelineReservation */
                $travelineReservation = $travelineReservations[$reservation->id];

                $oldHash = md5(json_encode($travelineReservation->data));
                $newDto = $this->convertHotelReservationToDto($reservation);
                $newHash = md5(json_encode($newDto));
                if ($oldHash === $newHash) {
                    return null;
                }

                //@todo уточнить по поводу проверки на отмененный статус
                $isCancelled = $reservation->status === $this->getCancelHotelReservationStatus();
                $travelineReservationStatus = $isCancelled ? TravelineReservationStatusEnum::Cancelled : TravelineReservationStatusEnum::Modified;

                return [
                    'reservation_id' => $reservation->id,
                    'data' => json_encode($newDto),
                    'status' => $travelineReservationStatus,
                    'created_at' => $reservation->created,
                    'updated_at' => now(),
                    'accepted_at' => null,
                ];
            })
            ->filter()
            ->chunk(100)
            ->each(function (Collection $travelineReservations) {
                TravelineReservation::upsert($travelineReservations->toArray(), 'reservation_id', ['data', 'status', 'updated_at', 'accepted_at']);
            });
    }

    private function createTravelineReservations(Collection $hotelReservations): void
    {
        $preparedReservations = $hotelReservations->map(fn(Reservation $reservation) => [
            'reservation_id' => $reservation->id,
            'data' => json_encode($this->convertHotelReservationToDto($reservation)),
            'status' => 'new',
            'created_at' => $reservation->created,
            'updated_at' => $reservation->created,
        ])->all();

        TravelineReservation::insert($preparedReservations);
    }

    private function convertHotelReservationToDto(Reservation $reservation): ReservationDto
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
                    new CarbonPeriod($reservation->date_checkin, $reservation->date_checkout)
                ),
                'status' => Dto\Reservation\StatusEnum::from($this->convertHotelReservationStatusToTravelineStatus($reservation->status)->value),
                'currencyCode' => env('DEFAULT_CURRENCY_CODE'),
            ]
        );
    }

    /**
     * @param Collection $rooms
     * @return RoomDto[]
     */
    private function convertHotelReservationsRoomsToDto(Collection $rooms, string $clientName, CarbonPeriod $period): array
    {
        return $rooms->map(function (Room $room) use ($clientName, $period) {
            return new RoomDto(
                $room->room_id,
                $room->rate_id,
                $this->covertRoomGuestsToDto($room->guests),
                $room->guests->count(),
                CustomerDto::from(['fullName' => $clientName]),
                $this->buildRoomPerDayPrices($period, $room->price_net),
                Dto\Reservation\Room\TotalDto::from(['amountAfterTaxes' => $room->price_net]),
                $room->note
            );
        })->all();
    }

    private function covertRoomGuestsToDto(Collection $guests)
    {
        $preparedGuests = $guests->map(fn(Guest $guest) => ['fullName' => $guest->fullname])->all();
        return Dto\Reservation\Room\GuestDto::collection($preparedGuests);
    }

    /**
     * @param CarbonPeriod $period
     * @return Dto\Reservation\Room\DayPriceDto[]
     */
    private function buildRoomPerDayPrices(CarbonPeriod $period, float $allDaysPrice): array
    {
        //@todo вынести в бронирование цену за один день (средняя стоимость дня) в домене
        $countDays = $period->count();
        $dailyPrice = $allDaysPrice / $countDays;
        $prices = [];
        foreach ($period as $date) {
            $prices[] = new Dto\Reservation\Room\DayPriceDto($date->format('Y-m-d'), $dailyPrice);
        }
        return $prices;
    }

    private function convertHotelReservationStatusToTravelineStatus(ReservationStatusEnum $status): TravelineReservationStatusEnum
    {
        return match ($status) {
            //@todo уточнить по поводу статусов
            $this->getCancelHotelReservationStatus() => TravelineReservationStatusEnum::Cancelled,
            ReservationStatusEnum::Created => TravelineReservationStatusEnum::New,
            default => TravelineReservationStatusEnum::Modified,
        };
    }

    private function getCancelHotelReservationStatus(): ReservationStatusEnum
    {
        return ReservationStatusEnum::WaitingCancellation;
    }
}
