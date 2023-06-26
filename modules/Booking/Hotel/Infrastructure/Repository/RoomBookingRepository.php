<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\Hotel\Infrastructure\Models\RoomBooking as Model;
use Module\Shared\Domain\ValueObject\Id;

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
        int $bookingId,
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): RoomBooking {
        $model = Model::create([
            'booking_id' => $bookingId,
            'data' => $this->serializeData($status, $roomInfo, $guests, $details, $price),
        ]);

        return $this->buildEntityFromModel($model);
    }

    public function store(RoomBooking $booking): bool
    {
        return (bool)Model::whereId($booking->id()->value())
            ->update([
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
            guests: $booking->guests(),
        );
    }

    private function serializeData(
        RoomBookingStatusEnum $status,
        RoomInfo $roomInfo,
        GuestCollection $guests,
        RoomBookingDetails $details,
        RoomPrice $price
    ): array {
        return [
            'status' => $status->value,
            'guests' => $guests->toData(),
            'roomInfo' => $roomInfo->toData(),
            'details' => $details->toData(),
            'price' => $price->toData()
        ];
    }

    private function buildEntityFromModel(Model $model): RoomBooking
    {
        $data = $model->data;

        return new RoomBooking(
            id: new Id($model->id),
            bookingId: new Id($model->booking_id),
            status: RoomBookingStatusEnum::from($data['status']),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guests: GuestCollection::fromData($data['guests']),
            details: RoomBookingDetails::fromData($data['details']),
            price: RoomPrice::fromData($data['price'])
        );
    }
}
