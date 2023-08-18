<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Module\Shared\Application\Service\ApplicationConstants;

class ConstantController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'constant';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->text('value', ['label' => 'Значение']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Расшифровка', 'renderer' => fn($r) => self::getConstantName($r->key)])
            ->text('value', ['text' => 'Значение']);
    }

    private static function getConstantName(string $key): string
    {
        foreach (ApplicationConstants::getInstance() as $constant) {
            if ($constant->key() === $key) {
                return $constant->name();
            }
        }

        return '';
    }
}
