<?php

declare(strict_types=1);

namespace App\Hotel\Support\Adapters\Booking;

use Pkg\Booking\Requesting\Application\UseCase\GetBookingRequests;
use Pkg\Booking\Requesting\Application\UseCase\GetDocumentFileInfo;

class RequestAdapter
{
    public function getBookingRequests(int $bookingId): array
    {
        return app(GetBookingRequests::class)->execute($bookingId);
    }

    public function getDocumentFileInfo(int $requestId): mixed
    {
        return app(GetDocumentFileInfo::class)->execute($requestId);
    }
}
