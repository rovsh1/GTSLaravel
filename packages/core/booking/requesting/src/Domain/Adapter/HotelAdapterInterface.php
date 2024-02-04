<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Adapter;

interface HotelAdapterInterface
{
    public function getAdministratorEmails(int $hotelId): array;
}
