<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Sdk\Shared\Enum\Hotel\MealPlanTypeEnum;

class MealPlanController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'meal-plan';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->localeText('name', ['label' => 'Наименование', 'required' => true])
            ->enum('type', ['label' => 'Тип', 'required' => true, 'enum' => MealPlanTypeEnum::class, 'emptyItem' => '']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->enum('type', ['text' => 'Тип', 'enum' => MealPlanTypeEnum::class])
            ->orderBy('name', 'asc');
    }
}
