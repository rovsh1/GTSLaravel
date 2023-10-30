<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation;

use Module\Integration\Traveline\Application\Dto\Reservation\Room\AdultsCast;
use Module\Integration\Traveline\Application\Dto\Reservation\Room\GuestDto;
use Module\Integration\Traveline\Application\Dto\Reservation\Room\TotalCast;
use Module\Integration\Traveline\Application\Dto\Reservation\Room\TotalDto;
use Sdk\Module\Foundation\Support\Dto\Attributes\MapInputName;
use Sdk\Module\Foundation\Support\Dto\Attributes\WithCast;
use Sdk\Module\Foundation\Support\Dto\Dto;
use Sdk\Module\Foundation\Support\Dto\DtoCollection;
use Sdk\Module\Foundation\Support\Dto\DtoCollectionOf;

class RoomDto extends Dto
{
    public function __construct(
        #[MapInputName('id')]
        public readonly int           $roomTypeId,

        #[MapInputName('rateId')]
        public readonly int           $ratePlanId,

        /** @var GuestDto[] $guests */
        #[DtoCollectionOf(Room\GuestDto::class)]
        public readonly DtoCollection $guests,

        #[MapInputName('guests'), WithCast(AdultsCast::class)]
        public readonly int           $adults,

        public readonly ?CustomerDto  $customer = null,
        public readonly ?array        $bookingPerDayPrices = null,

        #[MapInputName('priceNetto'), WithCast(TotalCast::class)]
        public readonly TotalDto      $total,

        #[MapInputName('guestNote')]
        public readonly ?string       $guestComment = null,

        public readonly int           $children = 0,
        public readonly int           $commission = 0,
    ) {}
}
