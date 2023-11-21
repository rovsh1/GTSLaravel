<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Client\InvoiceAdapter;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Module\Shared\Exception\ApplicationException;

class InvoiceController extends Controller
{
    public function create(int $orderId): AjaxResponseInterface
    {
        try {
            InvoiceAdapter::create($orderId);
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function cancel(int $orderId): AjaxResponseInterface
    {
        try {
            InvoiceAdapter::cancel($orderId);
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }

    public function get(int $orderId): JsonResponse
    {
        $invoice = InvoiceAdapter::get($orderId);

        return response()->json($invoice);
    }

    public function getFile(int $orderId): JsonResponse
    {
        $fileInfo = InvoiceAdapter::getFile($orderId);

        return response()->json($fileInfo);
    }

    public function send(int $orderId): AjaxResponseInterface
    {
        try {
            InvoiceAdapter::send($orderId);
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
