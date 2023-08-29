<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Order;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Order\SearchRequest;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function show(int $id): LayoutContract
    {
        $title = "Заказ №{$id}";

        return Layout::title($title)
            ->view($this->getPrototypeKey() . '.show.show', [
            ]);
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $orders = OrderAdapter::getActiveOrders($request->getClientId());

        return response()->json($orders);
    }

    protected function getPrototypeKey(): string
    {
        return 'booking-order';
    }
}
