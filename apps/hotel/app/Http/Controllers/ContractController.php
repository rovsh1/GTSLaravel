<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Models\Contract;
use App\Hotel\Support\Facades\Format;
use App\Hotel\Support\Facades\Grid;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\Grid\GridBuilder as GridContract;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sdk\Shared\Enum\Contract\StatusEnum;

class ContractController extends AbstractHotelController
{
    public function index(Request $request): LayoutContract
    {
        $hotel = $this->getHotel();

        $query = Contract::whereHotelId($hotel->id);
        $grid = $this->gridFactory()->data($query);

        return Layout::title('Договора')
            ->view('default.grid.grid', [
                'grid' => $grid,
                'hotel' => $hotel,
                'editAllowed' => false,
                'deleteAllowed' => false,
                'createUrl' => null,
            ]);
    }

    public function get(Contract $contract): JsonResponse
    {
        return response()->json($contract->load(['seasons']));
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(16)
            ->id('id', ['text' => 'ID', 'order' => true])
            ->text(
                'contract_number',
                ['text' => 'Номер', 'order' => true, 'renderer' => fn($r, $t) => (string)$r]
            )
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)])
            ->enum('status', ['text' => 'Статус', 'enum' => StatusEnum::class, 'order' => true])
            ->file('files', ['text' => 'Документы']);
    }
}
