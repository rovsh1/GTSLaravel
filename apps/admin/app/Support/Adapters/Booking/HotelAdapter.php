<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Core\Support\Adapters\AbstractModuleAdapter;
use Carbon\CarbonPeriod;
use Module\Booking\Hotel\Application\UseCase\UpdateExternalNumber;

class HotelAdapter extends AbstractModuleAdapter
{
    public function getBookings(array $filters = []): mixed
    {
        return $this->request('getBookings', []);
    }

    public function getBooking(int $id): mixed
    {
        return $this->request('getBooking', ['id' => $id]);
    }

    public function getBookingDetails(int $id): mixed
    {
        return $this->request('getBookingDetails', ['id' => $id]);
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        int $hotelId,
        CarbonPeriod $period,
        int $creatorId,
        ?int $orderId,
        ?string $note = null
    ): int {
        return $this->request('createBooking', [
            'cityId' => $cityId,
            'clientId' => $clientId,
            'hotelId' => $hotelId,
            'dateStart' => $period->getStartDate(),
            'dateEnd' => $period->getEndDate(),
            'creatorId' => $creatorId,
            'orderId' => $orderId,
            'note' => $note,
        ]);
    }

    public function addRoom(
        int $bookingId,
        int $roomId,
        int $rateId,
        int $status,
        bool $isResident,
        int $roomCount,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        $this->request('addRoom', [
            'id' => $bookingId,
            'roomId' => $roomId,
            'rateId' => $rateId,
            'status' => $status,
            'isResident' => $isResident,
            'roomCount' => $roomCount,
            'earlyCheckIn' => $earlyCheckIn,
            'lateCheckOut' => $lateCheckOut,
            'note' => $note,
            'discount' => $discount,
        ]);
    }

    public function updateRoom(
        int $bookingId,
        int $roomIndex,
        int $roomId,
        int $rateId,
        int $status,
        bool $isResident,
        int $roomCount,
        array|null $earlyCheckIn = null,
        array|null $lateCheckOut = null,
        ?string $note = null,
        ?int $discount = null
    ): void {
        $this->request('updateRoom', [
            'id' => $bookingId,
            'roomIndex' => $roomIndex,
            'roomId' => $roomId,
            'rateId' => $rateId,
            'status' => $status,
            'isResident' => $isResident,
            'roomCount' => $roomCount,
            'earlyCheckIn' => $earlyCheckIn,
            'lateCheckOut' => $lateCheckOut,
            'note' => $note,
            'discount' => $discount,
        ]);
    }

    public function deleteRoom(int $bookingId, int $roomIndex,): void
    {
        $this->request('deleteRoom', [
            'id' => $bookingId,
            'roomIndex' => $roomIndex,
        ]);
    }

    public function addRoomGuest(
        int $bookingId,
        int $roomIndex,
        string $fullName,
        int $countryId,
        int $gender
    ): void {
        $this->request('addRoomGuest', [
            'id' => $bookingId,
            'roomIndex' => $roomIndex,
            'fullName' => $fullName,
            'countryId' => $countryId,
            'gender' => $gender,
        ]);
    }

    public function updateRoomGuest(
        int $bookingId,
        int $roomIndex,
        int $guestIndex,
        string $fullName,
        int $countryId,
        int $gender
    ): void {
        $this->request('updateRoomGuest', [
            'id' => $bookingId,
            'roomIndex' => $roomIndex,
            'guestIndex' => $guestIndex,
            'fullName' => $fullName,
            'countryId' => $countryId,
            'gender' => $gender,
        ]);
    }

    public function updateExternalNumber(int $bookingId, int $type, ?string $number): void
    {
        app(UpdateExternalNumber::class)->execute($bookingId, $type, $number);
    }

    protected function getModuleKey(): string
    {
        return 'HotelBooking';
    }
}
