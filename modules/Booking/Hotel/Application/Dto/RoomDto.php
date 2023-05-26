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
        public readonly int $roomCount,
        public readonly mixed $earlyCheckIn = null,
        public readonly mixed $lateCheckOut = null,
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
            id: $room->id(),
            rateId: $room->rateId(),
            status: $room->status()->value,
            isResident: $room->isResident(),
            roomCount: $room->roomCount(),
            discount: $room->discount()->value(),
            guests: GuestDto::collectionFromDomain($room->guests()->all()),
            guestNote: $room->guestNote(),
            earlyCheckIn: $room->earlyCheckIn(),
            lateCheckOut: $room->lateCheckOut()
        );
    }
}
