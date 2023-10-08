<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Hotel;

use Module\Booking\Application\Admin\HotelBooking\UseCase\Request\GetBookingRequests;
use Module\Booking\Application\Admin\HotelBooking\UseCase\Request\GetDocumentFileInfo;
use Module\Booking\Application\Admin\HotelBooking\UseCase\Request\SendRequest;

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
