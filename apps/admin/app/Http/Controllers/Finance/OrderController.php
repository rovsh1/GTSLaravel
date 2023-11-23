<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Finance;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Finance\OrderAdapter;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function getWaitingPaymentOrders(int $paymentId): JsonResponse
    {
        $orders = OrderAdapter::getWaitingPaymentOrders($paymentId);

        return response()->json($orders);
    }
}
