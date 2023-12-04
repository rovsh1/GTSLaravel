<?php

namespace Supplier\Traveline\Application\Dto;

use Carbon\CarbonInterface;
use Sdk\Module\Foundation\Support\Dto\Attributes\MapInputName;
use Sdk\Module\Foundation\Support\Dto\Attributes\WithCast;
use Sdk\Module\Foundation\Support\Dto\Casts\CarbonInterfaceCast;
use Sdk\Module\Foundation\Support\Dto\Casts\EnumCast;
use Supplier\Traveline\Application\Dto\Reservation\PaymentMethodEnum;
use Supplier\Traveline\Application\Dto\Reservation\StatusEnum;

class ReservationDto
{
    public function __construct(
        #[MapInputName('id')]
        public readonly int $number,
        public readonly int $hotelId,

        #[MapInputName('createdDate'), WithCast(CarbonInterfaceCast::class)]
        public readonly CarbonInterface $created,

        #[MapInputName('checkInTime')]
        public readonly ?string $arrivalTime,

        #[MapInputName('checkOutTime')]
        public readonly ?string $departureTime,

        /** @var \Supplier\Traveline\Application\Dto\Reservation\RoomDto[] */
        #[MapInputName('rooms'), \Sdk\Module\Foundation\Support\Dto\DtoCollectionOf(\Supplier\Traveline\Application\Dto\Reservation\RoomDto::class)]
        public readonly ?\Sdk\Module\Foundation\Support\Dto\DtoCollection $roomStays = null,

        #[WithCast(EnumCast::class)]
        public readonly StatusEnum $status = StatusEnum::New,
        public readonly ?string $currencyCode = null,

        #[WithCast(EnumCast::class)]
        public readonly PaymentMethodEnum $paymentMethod = PaymentMethodEnum::Credit,
        public readonly ?string $paymentMethodComment = null,
    ) {}
}
