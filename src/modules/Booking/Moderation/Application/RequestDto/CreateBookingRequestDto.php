<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\RequestDto;

use Sdk\Shared\Enum\CurrencyEnum;

class CreateBookingRequestDto
{
    public readonly ?int $serviceId;

    public readonly ?int $hotelId;

    public function __construct(
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        ?int $serviceId,
        ?int $hotelId,
        public readonly int $administratorId,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?array $detailsData,
        public readonly ?string $note = null,
    ) {
        if ($serviceId === null && $hotelId === null) {
            throw new \InvalidArgumentException('Service id or hotel id is required');
        }
        $this->serviceId = $serviceId;
        $this->hotelId = $hotelId;
    }
}
