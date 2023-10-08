<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Module\Shared\Contracts\Service\CompanyRequisitesInterface;
use Module\Shared\Infrastructure\Service\CompanyRequisites\Entity\CompanyRequisiteInterface;

class CompanyRequisiteController extends AbstractPrototypeController
{
    private CompanyRequisitesInterface $requisites;

    public function __construct()
    {
        parent::__construct();
        $this->requisites = app(CompanyRequisitesInterface::class);
    }

    protected function getPrototypeKey(): string
    {
        return 'company-requisite';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->localeText('value', ['label' => 'Значение']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Расшифровка', 'renderer' => fn($r) => $this->getRequisite($r->key)?->name()])
            ->text('value', ['text' => 'Значение', 'renderer' => fn($r) => $this->castValue($r->key, $r->value)]);
    }

    private function getRequisite(string $key): ?CompanyRequisiteInterface
    {
        foreach ($this->requisites as $entity) {
            if ($entity->key() === $key) {
                return $entity;
            }
        }

        return null;
    }

    private function castValue(string $key, mixed $value)
    {
        return match ($this->getRequisite($key)->cast()) {
            'image' => '<img src="' . $value . '" alt="">',
            'email' => "<a href=\"mailto:$value\">$value</a>",
            default => $value,
        };
    }
}
