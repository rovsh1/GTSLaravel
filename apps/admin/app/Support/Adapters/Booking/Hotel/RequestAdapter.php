<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\HotelBooking\Application\UseCase\Admin\Request\GetBookingRequests;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Request\GetDocumentFileInfo;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Request\SendRequest;

class RequestAdapter
{
    public function sendRequest(int $bookingId): void
    {
        app(SendRequest::class)->execute($bookingId);
    }

    public function getBookingRequests(int $bookingId): array
    {
        return app(GetBookingRequests::class)->execute($bookingId);
    }

    public function getDocumentFileInfo(int $requestId): mixed
    {
        return app(GetDocumentFileInfo::class)->execute($requestId);
    }
}
