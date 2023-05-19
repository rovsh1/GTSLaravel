<?php

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Domain\Entity\Details\Room;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int     $id,
        /** @var GuestDto[] $guests */
        public readonly array   $guests,
        public readonly int     $rateId,
        public readonly float   $priceNetto,
        public readonly ?string $checkInTime,
        public readonly ?string $checkOutTime,
        public readonly ?string $guestNote,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Room $room): static
    {
        return new static(
            $room->id(),
            GuestDto::collectionFromDomain($room->guests()),
            $room->rateId(),
            $room->priceNetto()->value(),
            $room->checkInTime(),
            $room->checkOutTime(),
            $room->guestNote(),
        );
    }
}
