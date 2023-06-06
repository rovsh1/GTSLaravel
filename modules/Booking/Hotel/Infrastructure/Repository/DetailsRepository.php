<?php

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Module\Booking\Hotel\Domain\Entity\Details as Entity;
use Module\Booking\Hotel\Domain\Factory\DetailsFactory;
use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Module\Booking\Hotel\Infrastructure\Models\BookingDetails as Model;
use Module\Shared\Domain\Service\SerializerInterface;

class DetailsRepository implements DetailsRepositoryInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

    public function find(int $id): ?Entity
    {
        $model = Model::whereBookingId($id)->first();
        if (!$model) {
            return null;
        }
        return app(DetailsFactory::class)->createFrom($model);
    }

    public function update(Entity $details): bool
    {
        return (bool)Model::whereBookingId($details->id()->value())->update([
            'hotel_id' => $details->hotelInfo()->id(),
            'date_start' => $details->period()->dateFrom(),
            'date_end' => $details->period()->dateTo(),
            'nights_count' => $details->period()->nightsCount(),
            'data' => $this->serializer->serialize($details)
        ]);
    }
}
