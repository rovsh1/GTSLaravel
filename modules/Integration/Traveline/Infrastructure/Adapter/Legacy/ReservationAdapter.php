<?php

namespace Module\Integration\Traveline\Infrastructure\Adapter\Legacy;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Integration\Traveline\Application\Dto\ReservationDto;
use Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use Module\Integration\Traveline\Infrastructure\Models\Legacy\TravelineReservation;
use Module\Reservation\Common\Domain\Exception\ReservationNotFound;

class ReservationAdapter implements ReservationAdapterInterface
{
    /**
     * @param int $id
     * @return void
     * @throws \Throwable
     */
    public function confirmReservation(int $id, string $status): void
    {
        $existReservation = TravelineReservation::whereReservationId($id)->exists();
        if (!$existReservation) {
            throw new \Exception('Traveline reservation not found', 0, new ReservationNotFound());
        }
        TravelineReservation::whereReservationId($id)
            ->whereStatus($status)
            ->update(['accepted_at' => now()]);
    }

    public function getActiveReservations(): array
    {
        $reservations = TravelineReservation::whereNull('accepted_at')->get();

        $preparedReservations = $this->prepareReservationCollection($reservations);
        return ReservationDto::collection($preparedReservations)->all();
    }

    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        $reservations = TravelineReservation::whereNull('accepted_at')
            ->whereHotelId($hotelId)
            ->get();

        $preparedReservations = $this->prepareReservationCollection($reservations);
        return ReservationDto::collection($preparedReservations)->all();
    }

    public function getActiveReservationById(int $id): ?ReservationDto
    {
        $reservation = TravelineReservation::whereReservationId($id)->first();
        if ($reservation === null) {
            return null;
        }
        return ReservationDto::from($reservation->data);
    }

    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array
    {
        $reservations = TravelineReservation::query()
            ->where(function (Builder $builder) use ($hotelId) {
                if ($hotelId !== null) {
                    $builder->whereHotelId($hotelId);
                }
            })
            ->where('updated_at', '>=', $startDate)
            ->get();

        $preparedReservations = $this->prepareReservationCollection($reservations);
        return ReservationDto::collection($preparedReservations)->all();
    }

    private function prepareReservationCollection(Collection $collection): array
    {
        return $collection->map(fn(TravelineReservation $travelineReservation) => $travelineReservation->data)->all();
    }
}
