<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use Module\Shared\Application\Entity\CompanyRequisite\CompanyRequisiteInterface;
use Module\Shared\Application\Service\CompanyRequisites;

class CompanyRequisiteController extends AbstractPrototypeController
{
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
            ->text('name', ['text' => 'Расшифровка', 'renderer' => fn($r) => self::getRequisite($r->key)?->name()])
            ->text('value', ['text' => 'Значение', 'renderer' => fn($r) => self::castValue($r->key, $r->value)]);
    }

    private static function getRequisite(string $key): ?CompanyRequisiteInterface
    {
        foreach (CompanyRequisites::getInstance() as $entity) {
            if ($entity->key() === $key) {
                return $entity;
            }
        }

        return null;
    }

    private static function castValue(string $key, mixed $value)
    {
        return match (self::getRequisite($key)->cast()) {
            'image' => '<img src="' . $value . '" alt="">',
            'email' => "<a href=\"mailto:$value\">$value</a>",
            default => $value,
        };
    }
}
