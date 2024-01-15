<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Adapters;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Moderation\Application\UseCase\GetBooking;
use Module\Booking\Moderation\Application\UseCase\GetBookingByIds;
use Module\Booking\Moderation\Application\UseCase\HotelBooking\ConfirmBookingBySupplier;
use Pkg\Supplier\Traveline\Dto\ReservationDto;
use Pkg\Supplier\Traveline\Factory\BookingDtoFactory;
use Pkg\Supplier\Traveline\Models\TravelineReservation;

class BookingAdapter
{
    public function __construct(
        private readonly BookingDtoFactory $dtoFactory
    ) {}

    /**
     * Бронирования делаются в вашей системы из свободной квоты, передаются в средство размещения автоматически и не нуждаются в дополнительном подтверждении со стороны отеля. Задача данной функции обеспечить подтверждение факта успешности технической доставки бронирования из вашей системы в менеджер каналов.
     * Если менеджер каналов НЕ подтверждает получение брони ответом, содержащим «success»: true, то каналу необходимо хранить и отдавать в последующих запросах данное бронирование на своей стороне до момента подтверждения менеджером каналов его получения.
     * Менеджер каналов подтверждает факт успешного приема бронирований функцией GetBookingsActionRS.
     * @param int $id
     * @return void
     */
    public function confirmReservation(int $id, string $status): void
    {
        $existReservation = TravelineReservation::whereReservationId($id)->exists();
        if (!$existReservation) {
            throw new \RuntimeException('Traveline reservation not found', 0);
        }
        TravelineReservation::whereReservationId($id)
            ->whereStatus($status)
            ->update(['accepted_at' => now()]);

        /** @var Reservation $reservation */
        $reservation = Reservation::find($id);
        if ($status === TravelineReservationStatusEnum::New) {
            $reservation->changeStatus(ReservationStatusEnum::Confirmed);
            app(ConfirmBookingBySupplier::class)->execute($id);

            return;
        }
        if ($reservation->status === ReservationStatusEnum::WaitingProcessing) {
            $reservation->changeStatus(ReservationStatusEnum::Confirmed);
            app(ConfirmBookingBySupplier::class)->execute($id);
        }
    }

    public function getActiveReservations(): array
    {
        $reservationIds = TravelineReservation::whereNull('accepted_at')
            ->get()
            ->pluck('reservation_id')
            ->toArray();

        $reservations = app(GetBookingByIds::class)->execute($reservationIds);

        return $this->dtoFactory->collection($reservations);
    }

    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        $reservationIds = TravelineReservation::whereNull('accepted_at')
            ->whereHotelId($hotelId)
            ->get()
            ->pluck('reservation_id')
            ->toArray();

        $reservations = app(GetBookingByIds::class)->execute($reservationIds);

        return $this->dtoFactory->collection($reservations);
    }

    public function getReservationById(int $id): ?ReservationDto
    {
        $reservation = app(GetBooking::class)->execute($id);
        if ($reservation === null) {
            return null;
        }

        return $this->dtoFactory->build($reservation);
    }

    public function getUpdatedReservations(CarbonInterface $startDate, ?int $hotelId = null): array
    {
        $reservationIds = TravelineReservation::query()
            ->where(function (Builder $builder) use ($hotelId) {
                if ($hotelId !== null) {
                    $builder->whereHotelId($hotelId);
                }
            })
            //@todo дату обновления нужно проверять в таблице броней
            ->where('updated_at', '>=', $startDate)
            ->get()
            ->pluck('reservation_id')
            ->toArray();

        $reservations = app(GetBookingByIds::class)->execute($reservationIds);

        return $this->dtoFactory->collection($reservations);
    }
}
