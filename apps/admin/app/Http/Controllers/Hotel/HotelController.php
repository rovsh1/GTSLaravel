<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Enums\Hotel\RatingEnum;
use App\Admin\Enums\Hotel\StatusEnum;
use App\Admin\Enums\Hotel\VisibilityEnum;
use App\Admin\Models\Hotel\Reference\Type;
use App\Admin\Models\Hotel\User;
use App\Admin\Models\Reference\City;
use App\Admin\Support\Distance\Calculator;
use App\Admin\Support\Distance\Point;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Components\Helpers\HotelRating;
use App\Admin\View\Menus\HotelMenu;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Builder;

class HotelController extends AbstractPrototypeController
{
    public function __construct(
        private readonly Calculator $distanceCalculator
    ) {
        parent::__construct();
    }

    protected function getPrototypeKey(): string
    {
        return 'hotel';
    }

    public function index(): LayoutContract
    {
        return parent::index()
            ->script('hotel/main');
    }

    public function show(int $id): LayoutContract
    {
        return parent::show($id)
            ->addMetaName('hotel-landmark-base-route', route('hotels.landmark.store', $this->model))
            ->ss('hotel/show');
    }

    public function edit(int $id): LayoutContract
    {
        return parent::edit($id)
            ->script('hotel/edit')
            ->data([
                'cancelUrl' => $this->prototype->route('show', $id),
            ]);
    }

    public function create(): LayoutContract
    {
        return parent::create()
            ->script('hotel/edit');
    }

    protected function formFactory(): FormContract
    {
        $coordinates = isset($this->model) ? $this->model->coordinates : null;
        return Form::city('city_id', ['label' => 'Город', 'required' => true, 'emptyItem' => ''])
            ->hotelType('type_id', ['label' => 'Тип отеля', 'required' => true, 'emptyItem' => ''])
            ->checkbox('visible_for', ['label' => __('label.visible-for')])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->hotelRating('rating', ['label' => 'Категория', 'emptyItem' => ''])
            ->hotelStatus('status', ['label' => 'Статус', 'emptyItem' => ''])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            ->coordinates('coordinates', ['label' => 'Координаты', 'required' => true, 'value' => $coordinates])
            ->text('zipcode', ['label' => 'Индекс']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
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
//                    $contract = $row->activeContracts->first();
//                    if (!$contract) {
//                        return '-';
//                    }
//                    return (string)$contract . '<br />' . Format::period($contract->getPeriod()) . '';
//                }
            ])
            ->number('rooms_number', ['text' => 'Номеров', 'order' => true])
            ->number('booking_count', ['text' => 'Количество броней', 'order' => true])
            //->addColumn('status', 'enum', ['text' => 'Статус', 'enum' => 'HOTEL_STATUS', 'order' => true])
            ->orderBy('name', 'asc');
    }

    protected function getShowViewData(): array
    {
        $showUrl = $this->prototype->route('show', $this->model->id);
        $isUpdateAllowed = Acl::isUpdateAllowed($this->getPrototypeKey());
        return [
            'params' => $this->hotelParams($this->model),
            'contactsUrl' => $showUrl . '/contacts',
            'contactsEditable' => $isUpdateAllowed,
            'contacts' => $this->model->contacts,

            'notesUrl' => $isUpdateAllowed ? $showUrl . '/notes' : null,

            'hotelServices' => $this->model->services,
            'servicesEditable' => $isUpdateAllowed,
            'servicesUrl' => $showUrl . '/services',

            'hotelUsabilities' => $this->model->usabilities,
            'usabilitiesUrl' => $showUrl . '/usabilities',
            'usabilitiesEditable' => $isUpdateAllowed,

            'usersGrid' => $this->getUsersGrid(),
            'landmarkGrid' => $this->getLandmarkGrid(),
            'landmarkUrl' => $isUpdateAllowed ? route('hotels.landmark.create', ['hotel' => $this->model]) : null,
        ];
    }

    protected function prepareShowMenu($model)
    {
        Sidebar::submenu(new HotelMenu($model, 'info'));
    }

    protected function prepareGridQuery(Builder $query)
    {
        $query->withActiveContract();
    }

    protected function saving(array $data): array
    {
        $preparedData = $data;
        $cityId = $data['city_id'];
        $city = City::find($cityId);
        $from = new Point($city->center_lat, $city->center_lon);
        $to = Point::buildFromCoordinates($data['coordinates']);
        $preparedData['city_distance'] = $this->distanceCalculator->getDistance($from, $to);
        return $preparedData;
    }

    private function searchForm(): FormContract
    {
        return (new SearchForm())
            ->dateRange('period', ['label' => __('label.contract-period')])
            ->country('country_id', ['label' => __('label.country'), 'emptyItem' => ''])
            ->hidden('city_id', ['label' => __('label.city')])
            ->select('type_id', ['label' => __('label.type'), 'emptyItem' => '', 'items' => Type::get()])
//            ->numRange(
//                'booking_count',
//                ['label' => 'Кол-во броней', 'placeholder' => [__('label.from'), __('label.to')]]
//            )
            ->enum('status', ['label' => __('label.status'), 'emptyItem' => '', 'enum' => StatusEnum::class])
            ->enum('visibility', ['label' => __('label.visibility'), 'emptyItem' => '', 'enum' => VisibilityEnum::class])
            ->enum('rating', ['label' => __('label.rating'), 'emptyItem' => '', 'enum' => RatingEnum::class]);
    }

    private function hotelParams($model): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('name', 'Наименование')
            ->text('type_name', 'Категория')
            ->enum('visibility', 'Видимость', VisibilityEnum::class)
            ->enum('status', 'Статус', StatusEnum::class)
            ->custom('rating', 'Рейтинг', fn($v) => (new HotelRating($v))->render())
            ->custom('country_name', 'Страна', fn($v, $o) => "{$o['country_name']} / {$o['city_name']}")
            ->text('address', 'Адрес')
            ->text('zipcode', 'Индекс')
            ->date('created_at', 'Создан', ['format' => 'datetime'])
            ->data($model);
    }

    private function getUsersGrid(): GridContract
    {
        return Grid::paginator(self::GRID_LIMIT)
            ->text('presentation', ['text' => 'Имя в системе', 'order' => true])
            ->text('login', ['text' => 'Логин'])
            ->email('email', ['text' => 'Email'])
            ->phone('phone', ['text' => 'Телефон'])
            ->orderBy('presentation', 'asc')
            ->header(false)
            ->data(
                User::where('hotel_id', $this->model->id)
            );
    }

    public function getLandmarkGrid(): GridContract
    {
        return Grid::paginator(self::GRID_LIMIT)
            ->setOption('id', 'hotel-landmark-grid')
            ->text('name', ['text' => 'Наименование'])
            ->text('type_name', ['text' => 'Тип'])
            ->text('address', ['text' => 'Адрес'])
            ->number('distance', [
                'text' => 'Расстояние до отеля',
                'renderer' => fn($r) => Format::distance($r->distance),
            ])
            ->orderBy('name', 'asc')
            ->header(false)
            ->data($this->model->landmarks);
    }
}
