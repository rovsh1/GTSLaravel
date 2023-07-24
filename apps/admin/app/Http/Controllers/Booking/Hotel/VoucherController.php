<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Support\Facades\Booking\RequestAdapter;
use App\Admin\Support\Facades\Booking\VoucherAdapter;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use App\Core\Support\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;

class VoucherController
{
    public function sendVoucher(int $id): AjaxResponseInterface
    {
        try {
            VoucherAdapter::sendVoucher($id);
        } catch (\Throwable $e) {
            //@todo отлов доменных эксепшнов
            dd($e);
        }
        return new AjaxSuccessResponse();
    }

    public function getBookingVouchers(int $bookingId): JsonResponse
    {
        $requests = VoucherAdapter::getBookingVouchers($bookingId);
        return response()->json($requests);
    }

    public function getFileInfo(int $bookingId, int $voucherId): JsonResponse
    {
        $fileInfo = VoucherAdapter::getDocumentFileInfo($voucherId);
        return response()->json($fileInfo);
    }
}
