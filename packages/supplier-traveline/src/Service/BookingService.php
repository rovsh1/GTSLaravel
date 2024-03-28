<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Service;


use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Pkg\Supplier\Traveline\Adapters\BookingAdapter;
use Pkg\Supplier\Traveline\Dto\Request\Reservation;
use Pkg\Supplier\Traveline\Dto\ReservationDto;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Dto\Response\Error\TravelineResponseErrorInterface;
use Pkg\Supplier\Traveline\Repository\HotelRepository;

class BookingService
{
    private const NEW_PROD_MIN_RESERVATION_ID = 18000;
    private const NEW_PROD_RELEASE_DATE = '2024-03-31';

    /**
     * @var TravelineResponseErrorInterface[] $errors
     */
    private array $errors = [];

    public function __construct(
        private readonly HotelRepository $hotelRepository,
        private readonly BookingAdapter $adapter
    ) {}

    /**
     * @param int|null $reservationId
     * @param int|null $hotelId
     * @param CarbonInterface|null $dateUpdate
     * @return ReservationDto[]
     */
    public function getReservations(
        ?int $reservationId = null,
        ?int $hotelId = null,
        ?CarbonInterface $dateUpdate = null
    ): array {
        if ($hotelId !== null && !$this->hotelRepository->isHotelIntegrationEnabled($hotelId)) {
            throw new HotelNotConnectedException();
        }

        if ($reservationId < self::NEW_PROD_MIN_RESERVATION_ID) {
            $reservation = DB::table('traveline_reservations_old')->where('reservation_id', $reservationId)->first();
            if ($reservation === null) {
                return [];
            }
            $reservationData = json_decode($reservation->data, true);
            return [$reservationData];
        }

        $reservations = [];
        if ($reservationId === null && $hotelId === null && $dateUpdate === null) {
            $reservations = $this->adapter->getActiveReservations();
        } elseif ($dateUpdate !== null) {
            $reservations = $this->adapter->getUpdatedReservations($dateUpdate, $hotelId);

            if ($dateUpdate->isBefore($this->getNewProdReleaseDate())) {
                $oldReservations = DB::table('traveline_reservations_old')
                    ->where('updated_at', '>=', $dateUpdate)
                    ->when($hotelId !== null, fn($builder) => $builder->where('hotel_id', $hotelId))
                    ->get()
                    ->map(fn(\stdClass $reservation) => json_decode($reservation->data, true))
                    ->all();

                $reservations = array_merge($reservations, $oldReservations);
            }
        } elseif ($dateUpdate === null && $hotelId !== null) {
            $reservations = $this->adapter->getActiveReservationsByHotelId($hotelId);
        } elseif ($reservationId !== null) {
            $reservation = $this->adapter->getReservationById($reservationId);
            if ($reservation !== null) {
                $reservations[] = $reservation;
            }
        }

        return $reservations;
    }

    /**
     * @param Reservation[] $reservations
     * @return TravelineResponseErrorInterface[]
     */
    public function confirmReservations(array $reservations): array
    {
        foreach ($reservations as $reservationRequest) {
            try {
                $this->adapter->confirmReservation($reservationRequest->number, $reservationRequest->status);
            } catch (\Throwable $e) {
//                if (!$e->getPrevious() instanceof DomainEntityExceptionInterface) {
//                    throw $e;
//                }
//                if ($e->getPrevious()->domainCode() === ErrorCodeEnum::RESERVATION_NOT_FOUND) {
//                    $this->errors[] = new ReservationNotFound($reservationRequest->number);
//                }
                //@todo отлов других ошибок (пока непонятно какие могут быть ошибки)
            }
        }

        return $this->errors;
    }

    private function getNewProdReleaseDate(): CarbonImmutable
    {
        return new CarbonImmutable(self::NEW_PROD_RELEASE_DATE);
    }
}
