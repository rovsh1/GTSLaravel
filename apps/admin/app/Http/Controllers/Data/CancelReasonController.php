<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Models\Reference\CancelReason;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Illuminate\Http\JsonResponse;

class CancelReasonController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'cancel-reason';
    }

    public function list(): JsonResponse
    {
        return response()->json(
            CancelReason::all()
        );
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->checkbox('has_description', ['label' => 'Подробное описание']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true]);
    }
}
