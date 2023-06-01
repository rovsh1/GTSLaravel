<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Factory;

use Module\Booking\Hotel\Domain\Entity\Details;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Shared\Domain\Service\SerializerInterface;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class DetailsFactory extends AbstractEntityFactory
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    /** @var class-string<Details> */
    protected string $entity = Details::class;

    protected function fromArray(array $data): Details
    {
        $additionalData = $data['additional_data'] ?? null;
        if ($additionalData !== null) {
            $additionalData = $this->serializer->deserialize(AdditionalInfo::class, $data['additional_data']);
        }
        return new $this->entity(
            $data['booking_id'],
            $data['hotel_id'],
            new BookingPeriod(
                new \DateTime($data['date_start']),
                new \DateTime($data['date_end']),
                (int)$data['nights_count'],
            ),
            $additionalData,
            $this->serializer->deserialize(Details\RoomCollection::class, $data['rooms']),
            $this->serializer->deserialize(CancelConditions::class, $data['cancel_conditions']),
        );
    }
}
