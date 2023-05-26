<?php

namespace Module\Booking\Hotel\Infrastructure\Repository;

use Module\Booking\Hotel\Domain\Entity\Details as Entity;
use Module\Booking\Hotel\Domain\Factory\DetailsFactory;
use Module\Booking\Hotel\Domain\Repository\DetailsRepositoryInterface;
use Module\Booking\Hotel\Infrastructure\Models\BookingDetails as Model;
use Module\Shared\Infrastructure\Service\JsonSerializer;

class DetailsRepository implements DetailsRepositoryInterface
{
    public function __construct(
        private readonly JsonSerializer $serializer
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
        $additionalData = null;
        if ($details->additionalInfo() !== null) {
            $additionalData = $this->serializer->serialize($details->additionalInfo());
        }
        return (bool)Model::whereBookingId($details->id())->update([
            'hotel_id' => $details->hotelId(),
            'date_start' => $details->period()->dateFrom(),
            'date_end' => $details->period()->dateTo(),
            'additional_data' => $additionalData,
            'rooms' => $this->serializer->serialize($details->rooms())
        ]);
    }
}
