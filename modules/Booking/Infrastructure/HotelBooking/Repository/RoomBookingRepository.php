<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\HotelBooking\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingCollection;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingIdCollection;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Infrastructure\HotelBooking\Models\RoomBooking as Model;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomBookingRepository implements RoomBookingRepositoryInterface
{
    public function find(RoomBookingId $id): ?HotelRoomBooking
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    public function findOrFail(RoomBookingId $id): HotelRoomBooking
    {
        $entity = $this->find($id);
        if ($entity === null) {
            throw new EntityNotFoundException('Room booking not found');
        }

        return $entity;
    }

    public function get(RoomBookingIdCollection $roomBookingIds): RoomBookingCollection
    {
        $ids = $roomBookingIds->map(fn(RoomBookingId $id) => $id->value());
        /** @var Collection<int, Model> $models */
        $models = Model::whereIn('booking_hotel_rooms.id', $ids)->get();

        return new RoomBookingCollection($models->map(fn(Model $model) => $this->buildEntityFromModel($model))->all());
    }

    public function create(
        BookingId $bookingId,
        RoomInfo $roomInfo,
        RoomBookingDetails $details,
        RoomPrices $price
    ): HotelRoomBooking {
        $model = Model::create([
            'booking_id' => $bookingId->value(),
            'hotel_room_id' => $roomInfo->id(),
            'room_name' => $roomInfo->name(),
            'data' => $this->serializeData($roomInfo, $details, $price),
        ]);
        //hack потому что default scope не подтягивается после создания модели, а тут нужно, т.к. booking_order_id подгружается join'ом
        $model = Model::find($model->id);

        return $this->buildEntityFromModel($model);
    }

    public function store(HotelRoomBooking $booking): bool
    {
        return (bool)Model::whereId($booking->id()->value())
            ->update([
                'hotel_room_id' => $booking->roomInfo()->id(),
                'room_name' => $booking->roomInfo()->name(),
                'data' => $this->serializeEntity($booking)
            ]);
    }

    public function delete(RoomBookingId $id): bool
    {
        return (bool)Model::whereId($id->value())->delete();
    }

    private function serializeEntity(HotelRoomBooking $booking): array
    {
        return $this->serializeData(
            roomInfo: $booking->roomInfo(),
            details: $booking->details(),
            price: $booking->prices(),
        );
    }

    private function serializeData(
        RoomInfo $roomInfo,
        RoomBookingDetails $details,
        RoomPrices $price
    ): array {
        return [
            'roomInfo' => $roomInfo->toData(),
            'details' => $details->toData(),
            'price' => $price->toData()
        ];
    }

    private function buildEntityFromModel(Model $model): HotelRoomBooking
    {
        $data = $model->data;

        return new HotelRoomBooking(
            id: new RoomBookingId($model->id),
            bookingId: new BookingId($model->booking_id),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guestIds: GuestIdCollection::fromData($model->guest_ids),
            details: RoomBookingDetails::fromData($data['details']),
            prices: RoomPrices::fromData($data['price'])
        );
    }
}
