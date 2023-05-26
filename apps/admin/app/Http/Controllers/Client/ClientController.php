<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Enums\Client\TypeEnum;
use App\Admin\Http\Requests\Client\SearchRequest;
use App\Admin\Http\Resources\Client as ClientResource;
use App\Admin\Models\Client\Client;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;

class ClientController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'client';
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $clientsQuery = Client::orderBy('name');
        if ($request->getOrderId() !== null) {
            $order = OrderAdapter::findOrder($request->getOrderId());
            if (!empty($order)) {
                $clientsQuery->whereId($order->clientId);
            }
        }

        return response()->json(
            ClientResource::collection($clientsQuery->get())
        );
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'ФИО'])
            ->enum('type', ['text' => 'Тип', 'enum' => TypeEnum::class]);
    }

    protected function formFactory(): FormContract
    {
        return Form::text('name', ['label' => 'ФИО или название компании'])
            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class])
            ->city('city_id', ['label' => 'Город']);
    }
}
