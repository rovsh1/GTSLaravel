<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationIdCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Shared\Infrastructure\HotelBooking\Models\Accommodation as Model;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class AccommodationRepository implements AccommodationRepositoryInterface
{
    public function find(AccommodationId $id): ?HotelAccommodation
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    public function findOrFail(AccommodationId $id): HotelAccommodation
    {
        $entity = $this->find($id);
        if ($entity === null) {
            throw new EntityNotFoundException("Room booking [$id] not found");
        }

        return $entity;
    }

    public function get(AccommodationIdCollection $accommodationIds): AccommodationCollection
    {
        $ids = $accommodationIds->map(fn(AccommodationId $id) => $id->value());
        /** @var Collection<int, Model> $models */
        $models = Model::whereIn('booking_hotel_rooms.id', $ids)->get();

        return new AccommodationCollection($models->map(fn(Model $model) => $this->buildEntityFromModel($model))->all());
    }

    public function create(
        BookingId $bookingId,
        RoomInfo $roomInfo,
        AccommodationDetails $details,
        RoomPrices $price
    ): HotelAccommodation {
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

    public function store(HotelAccommodation $booking): bool
    {
        return (bool)Model::whereId($booking->id()->value())
            ->update([
                'hotel_room_id' => $booking->roomInfo()->id(),
                'room_name' => $booking->roomInfo()->name(),
                'data' => $this->serializeEntity($booking)
            ]);
    }

    public function delete(AccommodationId $id): bool
    {
        return (bool)Model::whereId($id->value())->delete();
    }

    private function serializeEntity(HotelAccommodation $booking): array
    {
        return $this->serializeData(
            roomInfo: $booking->roomInfo(),
            details: $booking->details(),
            price: $booking->prices(),
        );
    }

    private function serializeData(
        RoomInfo $roomInfo,
        AccommodationDetails $details,
        RoomPrices $price
    ): array {
        return [
            'roomInfo' => $roomInfo->toData(),
            'details' => $details->toData(),
            'price' => $price->toData()
        ];
    }

    private function buildEntityFromModel(Model $model): HotelAccommodation
    {
        $data = $model->data;

        return new HotelAccommodation(
            id: new AccommodationId($model->id),
            bookingId: new BookingId($model->booking_id),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guestIds: GuestIdCollection::fromData($model->guest_ids),
            details: AccommodationDetails::fromData($data['details']),
            prices: RoomPrices::fromData($data['price'])
        );
    }
}
