<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Models\System\MailTemplate;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\MailAdapter;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;

class MailTemplateController extends AbstractPrototypeController
{
    public function sendTest(MailTemplate $template)
    {
        $x = MailAdapter::sendTo('s16121986@yandex.ru', 'Test message', $template->body);
        dd($x);
    }

    protected function getPrototypeKey(): string
    {
        return 'mail-template';
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->select('key', [
                'label' => 'Шаблон',
                'emptyItem' => '',
                'required' => true,
                //'groupIndex' => 'group',
                //'groups' => self::getTemplateGroups(),
                'items' => array_map(function ($k) {
                    return (object)[
                        'value' => $k,
                        'group' => substr($k, 0, strpos($k, '_')),
                        'text' => __('mail.' . $k)
                    ];
                }, MailAdapter::getTemplatesList())
            ])
            ->language('language', ['label' => 'Язык', 'emptyItem' => ''])
            ->text('subject', ['label' => 'Заголовок', 'required' => true])
            ->hidden('body', ['render' => false]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('key', ['text' => 'Шаблон', 'renderer' => fn($r) => __('mail.' . $r->key), 'order' => true])
            ->text('subject', ['text' => 'Заголовок', 'order' => true])
            ->language('language', ['text' => 'Язык', 'order' => true]);
    }

    private static function getTemplateGroups(): array
    {
        return [
            ['id' => 'SITE', 'text' => 'Сайт']
        ];
    }
}
