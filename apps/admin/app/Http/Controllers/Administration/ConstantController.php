<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Module\Shared\Contracts\Service\ApplicationConstantsInterface;

class ConstantController extends AbstractPrototypeController
{
    private ApplicationConstantsInterface $constants;

    public function __construct()
    {
        parent::__construct();
        $this->constants = app(ApplicationConstantsInterface::class);
    }

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
            ->text('name', ['text' => 'Расшифровка', 'renderer' => fn($r) => $this->getConstantName($r->key)])
            ->text('value', ['text' => 'Значение']);
    }

    private function getConstantName(string $key): string
    {
        foreach ($this->constants as $constant) {
            if ($constant->key() === $key) {
                return $constant->name();
            }
        }

        return '';
    }
}
