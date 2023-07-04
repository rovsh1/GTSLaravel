<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Dto;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Module\Booking\Airport\Application\Dto\Details\AirportInfoDto;
use Module\Booking\Airport\Application\Dto\Details\ServiceInfoDto;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Common\Application\Response\BookingDto as BaseDto;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class BookingDto extends BaseDto
{
    public function __construct(
        int $id,
        int $status,
        int $orderId,
        CarbonImmutable $createdAt,
        int $creatorId,
        public readonly ?string $note,
        public readonly AirportInfoDto $airportInfo,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly CarbonInterface $date,
    ) {
        parent::__construct($id, $status, $orderId, $createdAt, $creatorId);
    }

    public static function fromDomain(EntityInterface|BookingInterface|ValueObjectInterface|Booking $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->status()->value,
            $entity->orderId()->value(),
            $entity->createdAt(),
            $entity->creatorId()->value(),
            $entity->note(),
            AirportInfoDto::fromDomain($entity->airportInfo()),
            ServiceInfoDto::fromDomain($entity->serviceInfo()),
            $entity->date(),
        );
    }
}
