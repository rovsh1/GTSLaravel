<?php

namespace App\Admin\Http\Controllers\Site;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class FaqController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'site-faq';
    }

    protected function formFactory(): FormContract
    {
        return Form::localeText('question', ['label' => 'Випрос', 'required' => true])
            ->localeTextarea('answer', ['label' => 'Ответ', 'required' => true])
            ->select('type', ['label' => 'Тип', 'required' => true, 'emptyItem' => '', 'items' => [
                ['id' => 1, 'text' => 'Профиль'],
                ['id' => 2, 'text' => 'Сайт'],
            ]]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('question', ['text' => 'Випрос', 'order' => true])
            ->text('type', ['text' => 'Тип']);
    }
}
