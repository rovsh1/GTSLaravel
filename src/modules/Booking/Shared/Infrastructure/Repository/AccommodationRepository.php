<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Shared\Infrastructure\Models\Accommodation as Model;
use Module\Shared\Support\RepositoryInstances;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class AccommodationRepository implements AccommodationRepositoryInterface
{
    private RepositoryInstances $instances;

    private array $bookingMapping = [];

    public function __construct()
    {
        $this->instances = new RepositoryInstances();
    }

    public function find(AccommodationId $id): ?HotelAccommodation
    {
        if ($this->instances->has($id)) {
            return $this->instances->get($id);
        }

        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    public function findOrFail(AccommodationId $id): HotelAccommodation
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Room booking [$id] not found");
    }

    public function getByBookingId(BookingId $bookingId): AccommodationCollection
    {
        if (isset($this->bookingMapping[$bookingId->value()])) {
            return new AccommodationCollection(
                array_map(fn($id) => $this->findOrFail(new AccommodationId($id)),
                    $this->bookingMapping[$bookingId->value()])
            );
        }

        $models = Model::whereBookingId($bookingId->value())->get()->all();

        $this->bookingMapping[$bookingId->value()] = array_map(fn($r) => $r->id, $models);

        return new AccommodationCollection(array_map(fn(Model $model) => $this->buildEntityFromModel($model), $models));
    }

    public function get(): array
    {
        return $this->instances->all();
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
        $this->instances->remove($id);

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

        $accommodation = new HotelAccommodation(
            id: new AccommodationId($model->id),
            bookingId: new BookingId($model->booking_id),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guestIds: GuestIdCollection::fromData($model->guest_ids),
            details: AccommodationDetails::fromData($data['details']),
            prices: RoomPrices::fromData($data['price'])
        );

        $this->instances->add($accommodation->id(), $accommodation);

        return $accommodation;
    }
}
