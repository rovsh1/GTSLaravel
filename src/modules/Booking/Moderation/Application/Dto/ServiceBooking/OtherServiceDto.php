<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

class OtherServiceDto implements ServiceDetailsDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ServiceInfoDto $serviceInfo,
        public readonly ?string $description,
    ) {}
}
