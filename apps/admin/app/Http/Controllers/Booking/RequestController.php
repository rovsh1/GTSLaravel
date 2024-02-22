<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking;

use App\Admin\Support\Facades\Booking\RequestAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;

class RequestController
{
    public function sendRequest(int $id): AjaxResponseInterface
    {
        try {
            RequestAdapter::sendRequest($id);
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }
        return new AjaxSuccessResponse();
    }

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
