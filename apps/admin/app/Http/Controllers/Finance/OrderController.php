<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Finance;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Finance\LendOrdersRequest;
use App\Admin\Support\Facades\Finance\OrderAdapter;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Module\Shared\Exception\ApplicationException;

class OrderController extends Controller
{
    public function getWaitingPaymentOrders(int $paymentId): JsonResponse
    {
        $orders = OrderAdapter::getWaitingPaymentOrders($paymentId);

        return response()->json($orders);
    }

    public function lendOrders(int $paymentId, LendOrdersRequest $request): AjaxResponseInterface
    {
        try {
            OrderAdapter::lendOrders($paymentId, $request->getOrders());
        } catch (ApplicationException $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxSuccessResponse();
    }
}
