<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Invoice;

use App\Admin\Support\Facades\Client\InvoiceAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use Illuminate\Http\RedirectResponse;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceStatusEnum;

class InvoiceController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'invoice';
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()->method('post');

        $form->trySubmit($this->prototype->route('create'));

        $preparedData = $this->saving($form->getData());
        $clientId = $preparedData['client_id'];
        $orderIds = $preparedData['order_ids'];
        InvoiceAdapter::create($clientId, $orderIds);

        $redirectUrl = $this->prototype->route('index');

        return redirect($redirectUrl);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
//            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->id('id', ['text' => '№', 'order' => true])
            ->text('client_name', ['text' => 'Клиент', 'order' => true])
            ->enum('status', ['text' => 'Статус', 'enum' => InvoiceStatusEnum::class])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->orderBy('id', 'desc');
    }

    protected function formFactory(): FormContract
    {
        return Form::client('client_id', ['label' => 'Клиент', 'required' => true, 'emptyItem' => ''])
            ->hiddenMultiSelect('order_ids', ['label' => 'Заказы', 'required' => true]);
    }

    private function searchForm()
    {
        return (new SearchForm());
//            ->select('country_id', ['label' => 'Страна', 'emptyItem' => '', 'items' => Country::get()])
//            ->city('city_id', ['label' => 'Город', 'emptyItem' => ''])
//            ->enum('type', ['label' => 'Тип', 'enum' => TypeEnum::class, 'emptyItem' => ''])
//            ->enum('status', ['label' => 'Источник', 'enum' => StatusEnum::class, 'emptyItem' => ''])
//            ->select('markup_group_id', [
//                'label' => 'Группа наценки',
//                'emptyItem' => '',
//                'items' => MarkupGroup::get()
//            ]);
    }
}
