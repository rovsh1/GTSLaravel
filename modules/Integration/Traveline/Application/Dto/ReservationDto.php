<?php

namespace Module\Integration\Traveline\Application\Dto;

use Carbon\CarbonInterface;
use Custom\Framework\Foundation\Support\Dto\Attributes\MapInputName;
use Custom\Framework\Foundation\Support\Dto\Attributes\WithCast;
use Custom\Framework\Foundation\Support\Dto\Casts\CarbonInterfaceCast;
use Custom\Framework\Foundation\Support\Dto\Casts\EnumCast;
use Custom\Framework\Foundation\Support\Dto\Dto;
use Custom\Framework\Foundation\Support\Dto\DtoCollection;
use Custom\Framework\Foundation\Support\Dto\DtoCollectionOf;
use Module\Integration\Traveline\Application\Dto\Reservation\CustomerDto;
use Module\Integration\Traveline\Application\Dto\Reservation\PaymentMethodEnum;
use Module\Integration\Traveline\Application\Dto\Reservation\StatusEnum;

class ReservationDto extends Dto
{
    public function __construct(
        #[MapInputName('id')]
        public readonly int               $number,
        public readonly int               $hotelId,

        #[MapInputName('createdDate'), WithCast(CarbonInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public readonly CarbonInterface   $created,

        #[MapInputName('checkInTime')]
        public readonly ?string           $arrivalTime,

        #[MapInputName('checkOutTime')]
        public readonly ?string           $departureTime,

        /** @var Reservation\RoomDto[] */
        #[MapInputName('rooms'), DtoCollectionOf(Reservation\RoomDto::class)]
        public readonly ?DtoCollection    $roomStays = null,

        #[MapInputName('customer')]
        public readonly ?CustomerDto $customer = null,

        #[WithCast(EnumCast::class)]
        public readonly StatusEnum        $status = StatusEnum::New,
        public readonly ?string           $currencyCode = null,

        #[WithCast(EnumCast::class)]
        public readonly PaymentMethodEnum $paymentMethod = PaymentMethodEnum::Credit,
        public readonly ?string           $paymentMethodComment = null,
    ) {}
}
