<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Booking\HotelBooking\Infrastructure\Models\RoomBooking as Model;
use Module\Booking\Order\Domain\ValueObject\GuestIdsCollection;

class RoomBookingRepository implements RoomBookingRepositoryInterface
{
    public function find(int $id): ?RoomBooking
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    public function get(int $bookingId): RoomBookingCollection
    {
        /** @var Collection<int, Model> $models */
        $models = Model::whereBookingId($bookingId)->get();

        return new RoomBookingCollection($models->map(fn(Model $model) => $this->buildEntityFromModel($model))->all());
    }

    public function create(
        BookingId $bookingId,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        RoomBookingDetails $details,
        RoomPrice $price
    ): RoomBooking {
        $model = Model::create([
            'booking_id' => $bookingId->value(),
            'hotel_room_id' => $roomInfo->id(),
            'room_name' => $roomInfo->name(),
            'data' => $this->serializeData($status, $roomInfo, $details, $price),
        ]);
        //hack потому что default scope не подтягивается после создания модели, а тут нужно, т.к. booking_order_id подгружается join'ом
        $model = Model::find($model->id);

        return $this->buildEntityFromModel($model);
    }

    public function store(RoomBooking $booking): bool
    {
        return (bool)Model::whereId($booking->id()->value())
            ->update([
                'hotel_room_id' => $booking->roomInfo()->id(),
                'room_name' => $booking->roomInfo()->name(),
                'data' => $this->serializeEntity($booking)
            ]);
    }

    public function delete(int $id): bool
    {
        return (bool)Model::whereId($id)->delete();
    }

    private function serializeEntity(RoomBooking $booking): array
    {
        return $this->serializeData(
            status: $booking->status(),
            roomInfo: $booking->roomInfo(),
            details: $booking->details(),
            price: $booking->price(),
        );
    }

    private function serializeData(
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        RoomBookingDetails $details,
        RoomPrice $price
    ): array {
        return [
            'status' => $status->value,
            'roomInfo' => $roomInfo->toData(),
            'details' => $details->toData(),
            'price' => $price->toData()
        ];
    }

    private function buildEntityFromModel(Model $model): RoomBooking
    {
        $data = $model->data;

        return new RoomBooking(
            id: new RoomBookingId($model->id),
            bookingId: new BookingId($model->booking_id),
            orderId: new OrderId($model->booking_order_id),
            status: RoomBookingStatusEnum::from($data['status']),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guestsIds: GuestIdsCollection::fromData($model->guest_ids),
            details: RoomBookingDetails::fromData($data['details']),
            price: RoomPrice::fromData($data['price'])
        );
    }
}
