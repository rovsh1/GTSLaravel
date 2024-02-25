<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Supplier;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Supplier\LendBookingsRequest;
use App\Admin\Support\Facades\Supplier\BookingAdapter;
use App\Admin\Support\Facades\Supplier\PaymentAdapter;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\JsonResponse;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\ValueObject\Money;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingController extends Controller
{
    public function getWaitingPaymentBookings(int $paymentId): JsonResponse
    {
        $orders = BookingAdapter::getWaitingPaymentBookings($paymentId);

        return response()->json($orders);
    }

    public function getPaymentBookings(int $paymentId): JsonResponse
    {
        $orders = BookingAdapter::getPaymentBookings($paymentId);

        return response()->json($orders);
    }

    public function lendBookings(int $paymentId, LendBookingsRequest $request): AjaxResponseInterface
    {
        $payment = PaymentAdapter::get($paymentId);
        if ($payment === null) {
            throw new NotFoundHttpException('Payment not found');
        }
        $paymentCurrency = CurrencyEnum::from($payment->payedAmount->currency->value);
        $preparedBookings = array_map(fn(array $order) => [
            'id' => $order['id'],
            'sum' => Money::round($paymentCurrency, $order['sum'])
        ], $request->getBookings());
        BookingAdapter::lendBookings($paymentId, $preparedBookings);

        return new AjaxSuccessResponse();
    }
}
