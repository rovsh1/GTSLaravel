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
    //@todo
    /**
     * Юзкейсы
     * 1. Получить заказ
     * 2. Получить список броней заказа (без деталей)
     * 3. Получить статусы заказа
     * 4. Получить доступные действия с заказом
     * 5. Получить список ваучеров
     * 6. Получить список инвоисов
     */

    public function show(int $id): LayoutContract
    {
        $title = "Заказ №{$id}";

        return Layout::title($title)
            ->view($this->getPrototypeKey() . '.show.show', []);
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
