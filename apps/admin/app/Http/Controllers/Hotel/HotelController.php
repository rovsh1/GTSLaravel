<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Models\Hotel\Type;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid;
use App\Admin\Support\View\Grid\Search;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Components\HotelRating;
use App\Admin\View\Menus\HotelMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Http\RedirectResponse;

class HotelController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel';
    }

    public function create(): LayoutContract
    {
        Layout::addMetaName('google-maps-key', env('GOOGLE_MAPS_API_KEY'));
        Layout::script('hotel/create');
        Layout::style('hotel/create');
        return parent::create();
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()->method('post');
        if (!$form->submit()) {
            return redirect($this->prototype->route('create'))
                ->withErrors($form->errors())
                ->withInput();
        }
        dd($form->getFormData());

        return redirect($this->prototype->route());
    }

    protected function formFactory(): FormContract
    {
        return Form::city('city_id', ['label' => 'Город', 'required' => true, 'emptyItem' => ''])
            ->hotelType('type_id', ['label' => 'Тип отеля', 'required' => true, 'emptyItem' => ''])
            ->checkbox('visible_for', ['label' => 'Виден только для B2B'])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->hotelRating('rating', ['label' => 'Категория', 'emptyItem' => ''])
            ->hotelStatus('status', ['label' => 'Статус', 'emptyItem' => ''])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            ->coordinates('coordinates', ['label' => 'Координаты', 'required' => true])
            ->text('zipcode', ['label' => 'Индекс']);
    }

    protected function gridFactory(): Grid
    {
        return (new Grid())
            ->enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->text('name', [
                'text' => 'Наименование',
                'route' => $this->prototype->routeName('show')
            ])
            ->text('city_name', ['text' => 'Город', 'order' => true])
            ->text('type_name', ['text' => 'Тип', 'order' => true])
            ->text('rating', [
                'text' => 'Категория',
                'renderer' => fn($r, $v) => (new HotelRating($v))->render(),
                'order' => true
            ])
            //->addColumn('address', 'text', ['text' => lang('Address')])
            ->text('contract', [
                'text' => 'Договор',
//                'renderer' => function ($row) {
//                    $contract = $row->getContract();
//                    if (!$contract->findActive()) {
//                        return '-';
//                    }
//                    return (string)$contract . '<br />' . \format\period($contract->date_from, $contract->date_to) . '';
//                }
            ])
            ->number('rooms_number', ['text' => 'Номеров', 'order' => true])
            ->number('reservation_count', ['text' => 'Количество броней', 'order' => true])
            //->addColumn('status', 'enum', ['text' => 'Статус', 'enum' => 'HOTEL_STATUS', 'order' => true])
            ->orderBy('name', 'asc');
    }

    protected function getShowViewData($model)
    {
        return [
            'params' => $this->hotelParams($model),
            'model' => $model
        ];
    }

    protected function prepareShow($model)
    {
        Sidebar::submenu(new HotelMenu($model, 'info'));
    }

    private function searchForm()
    {
        return (new Search())
            //->addElement('period', 'daterange', ['label' => 'Период договора'])
            ->country('country_id', ['label' => __('label.country'), 'emptyItem' => ''])
            ->hidden('city_id', ['label' => __('label.city')])
            ->select('type_id', [
                'label' => 'Тип',
                'emptyItem' => '',
                'items' => Type::get()
            ])
//            ->addElement('reservation_count', 'numrange', ['label' => 'Кол-во броней', 'placeholder' => ['от', 'до']])
//            ->addElement('status', 'enum', [
//                'label' => 'Статус',
//                'emptyItem' => '',
//                'enum' => 'HOTEL_STATUS'
//            ])
//            ->addElement('visible_for', 'enum', [
//                'label' => 'Для клиентов',
//                'emptyItem' => 'Для всех',
//                'enum' => 'HOTEL_VISIBLE_FOR'
//            ])
//            ->addElement('rating', 'select', [
//                'label' => 'Категория',
//                'emptyItem' => '',
//                'items' => HotelFormService::getRatingItems()
//            ])
            ;
    }

    private function hotelParams($model)
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->text('type_name', 'Категория')
            ->custom('rating', 'Рейтинг', fn($v) => (new HotelRating($v))->render())
            //->custom('country', 'Страна', fn($v) => (new HotelRating($v))->render())
            ->text('address', 'Адрес')
            ->text('zipcode', 'Индекс')
            ->date('created', 'Создан', ['format' => 'datetime'])
            ->data($model);
    }
}
