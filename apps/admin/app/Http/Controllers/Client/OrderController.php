<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Client\LendOrdersRequest;
use App\Admin\Support\Facades\Client\OrderAdapter;
use App\Admin\Support\Facades\Client\PaymentAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\ValueObject\Money;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $payment = PaymentAdapter::get($paymentId);
        if ($payment === null) {
            throw new NotFoundHttpException('Payment not found');
        }
        $paymentCurrency = CurrencyEnum::from($payment->payedAmount->currency->value);
        $preparedOrders = array_map(fn(array $order) => [
            'id' => $order['id'],
            'sum' => Money::round($paymentCurrency, $order['sum'])
        ], $request->getOrders());
        OrderAdapter::lendOrders($paymentId, $preparedOrders);

        return new AjaxSuccessResponse();
    }
}
