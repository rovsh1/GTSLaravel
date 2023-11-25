<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Finance;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Finance\LendOrdersRequest;
use App\Admin\Support\Facades\Finance\OrderAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function getWaitingPaymentOrders(int $paymentId): JsonResponse
    {
        $orders = OrderAdapter::getWaitingPaymentOrders($paymentId);

        return response()->json($orders);
    }

    public function getPaymentOrders(int $paymentId): JsonResponse
    {
        $orders = OrderAdapter::getPaymentOrders($paymentId);

        return response()->json($orders);
    }

    public function lendOrders(int $paymentId, LendOrdersRequest $request): AjaxResponseInterface
    {
        OrderAdapter::lendOrders($paymentId, $request->getOrders());

        return new AjaxSuccessResponse();
    }
}
