<?php

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Domain\Entity\Details\Room;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $rateId,
        public readonly int $status,
        public readonly bool $isResident,
        public readonly ?int $discount,
        /** @var GuestDto[] $guests */
        public readonly array $guests,
        public readonly ?string $guestNote,
//        public readonly float   $priceNetto,
//        public readonly ?string $checkInTime,
//        public readonly ?string $checkOutTime,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Room $room): static
    {
        return new static(
            $room->id(),
            $room->rateId(),
            $room->status()->value,
            $room->isResident(),
            $room->discount()->value(),
            GuestDto::collectionFromDomain($room->guests()->all()),
            $room->guestNote(),
//            $room->priceNetto()->value(),
//            $room->checkInTime(),
//            $room->checkOutTime(),
        );
    }
}
