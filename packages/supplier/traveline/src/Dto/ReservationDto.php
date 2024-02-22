<?php

namespace Pkg\Supplier\Traveline\Dto;

use Carbon\CarbonInterface;
use Pkg\Supplier\Traveline\Dto\Reservation\CustomerDto;
use Pkg\Supplier\Traveline\Dto\Reservation\PaymentMethodEnum;
use Pkg\Supplier\Traveline\Models\TravelineReservationStatusEnum;

class ReservationDto
{
    public function __construct(
        public readonly int $number,
        public readonly int $hotelId,

        public readonly CarbonInterface $created,

        public readonly ?string $arrivalTime,
        public readonly ?string $departureTime,

        /** @var \Pkg\Supplier\Traveline\Dto\Reservation\RoomStayDto[]|null $roomStays */
        public readonly ?array $roomStays = null,

        #[MapInputName('additionalInfo')]
        public readonly ?string $additionalInfo = null,

        #[MapInputName('customer')]
        public readonly ?CustomerDto $customer = null,

        public readonly TravelineReservationStatusEnum $status = TravelineReservationStatusEnum::NEW,
        public readonly ?string $currencyCode = null,

        public readonly PaymentMethodEnum $paymentMethod = PaymentMethodEnum::Prepay,
        public readonly ?string $paymentMethodComment = null,
    ) {}
}
