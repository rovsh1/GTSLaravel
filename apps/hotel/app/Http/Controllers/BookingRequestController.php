<?php

declare(strict_types=1);

namespace App\Hotel\Http\Controllers;

use App\Hotel\Support\Facades\Booking\RequestAdapter;
use Illuminate\Http\JsonResponse;

class BookingRequestController
{
    public function getBookingRequests(int $bookingId): JsonResponse
    {
        $requests = RequestAdapter::getBookingRequests($bookingId);

        return response()->json($requests);
    }

    public function getFileInfo(int $bookingId, int $requestId): JsonResponse
    {
        $fileInfo = RequestAdapter::getDocumentFileInfo($requestId);

        return response()->json($fileInfo);
    }
}
