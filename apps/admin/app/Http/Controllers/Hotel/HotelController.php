<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\InvalidPointCoordinates;
use App\Admin\Exceptions\InvalidZipcode;
use App\Admin\Http\Requests\Hotel\SearchRequest;
use App\Admin\Http\Requests\Hotel\UpdateSettingsRequest;
use App\Admin\Http\Resources\Hotel as HotelResource;
use App\Admin\Http\Resources\Room;
use App\Admin\Models\Hotel\Contract;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\Type;
use App\Admin\Models\Hotel\User;
use App\Admin\Models\Reference\City;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Support\Distance\Calculator;
use App\Admin\Support\Distance\Point;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Hotel\SettingsAdapter;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Components\Helpers\HotelRating;
use App\Admin\View\Menus\HotelMenu;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Gsdk\Format\View\ParamsTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sdk\Shared\Enum\Hotel\StatusEnum;
use Sdk\Shared\Enum\Hotel\VisibilityEnum;

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
        return parent::index()->view('hotel.main.main');
    }

    public function edit(int $id): LayoutContract
    {
        return parent::edit($id)
            ->view($this->prototype->view('form'))
            ->data([
                'cancelUrl' => $this->prototype->route('show', $id),
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        try {
            return parent::update($id);
        } catch (InvalidPointCoordinates) {
            $this->form()->error('Указаны некорректные координаты.');
            $this->form()->throwError();
        } catch (InvalidZipcode) {
            $this->form()->error('Указан некорректный индекс.');
            $this->form()->throwError();
        }
    }

    public function create(): LayoutContract
    {
        return parent::create()->view($this->prototype->view('form'));
    }

    public function store(): RedirectResponse
    {
        try {
            return parent::store();
        } catch (InvalidPointCoordinates) {
            $this->form()->error('Указаны некорректные координаты.');
            $this->form()->throwError();
        } catch (InvalidZipcode) {
            $this->form()->error('Указан некорректный индекс.');
            $this->form()->throwError();
        }
    }

    public function get(Request $request, Hotel $hotel): JsonResponse
    {
        return response()->json($hotel);
    }

    public function settings(Request $request, int $id): JsonResponse
    {
        $hotelSettings = SettingsAdapter::getHotelSettings($id);

        return response()->json($hotelSettings);
    }

    public function updateSettings(UpdateSettingsRequest $request, int $id): AjaxResponseInterface
    {
        SettingsAdapter::updateHotelTimeSettings(
            $id,
            $request->getCheckInAfter(),
            $request->getCheckOutBefore(),
            $request->getBreakfastPeriodFrom(),
            $request->getBreakfastPeriodTo()
        );

        return new AjaxSuccessResponse();
    }

    public function getRooms(Request $request, Hotel $hotel): JsonResponse
    {
        return response()->json(
            Room::collection($hotel->rooms)
        );
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $hotelsQuery = Hotel::query();
        if ($request->getCityId() !== null) {
            $hotelsQuery->whereCityId($request->getCityId());
        }

        return response()->json(
            HotelResource::collection($hotelsQuery->get())
        );
    }

    protected function formFactory(): FormContract
    {
        $coordinates = isset($this->model) ? $this->model->coordinates : null;

        return Form::select('supplier_id', [
            'label' => 'Поставщик',
            'required' => true,
            'emptyItem' => '',
            'items' => Supplier::get()
        ])
            ->city('city_id', ['label' => 'Город', 'required' => true, 'emptyItem' => ''])
            ->select('type_id', ['label' => 'Тип отеля', 'required' => true, 'emptyItem' => '', 'items' => Type::get()])
            ->currency('currency', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->enum('visibility', [
                'label' => __('label.visibility'),
                'emptyItem' => '',
                'enum' => VisibilityEnum::class
            ])
            ->text('name', ['label' => 'Наименование', 'required' => true])
            ->rating('rating', ['label' => 'Категория', 'emptyItem' => ''])
            ->enum('status', ['label' => 'Статус', 'emptyItem' => '', 'enum' => StatusEnum::class, 'required' => true])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            ->text('address_en', ['label' => 'Адрес (EN)', 'required' => true])
            ->coordinates('coordinates', ['label' => 'Координаты', 'required' => true, 'value' => $coordinates])
            ->text('zipcode', ['label' => 'Индекс'])
            ->checkbox('is_traveline_integration_enabled', ['label' => 'Интеграция с Traveline']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->travelineBadge('is_traveline_integration_enabled', ['text' => 'TL'])
            ->text('name', [
                'text' => 'Наименование',
                'route' => $this->prototype->routeName('show'),
                'order' => true
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
                'renderer' => function ($row) {
                    /** @var Contract|null $contract */
                    $contract = $row->contracts->first();
                    if (!$contract) {
                        return '-';
                    }

                    return $contract . '<br>' . Format::period($contract->period);
                }
            ])
            ->text('rooms_count', ['text' => 'Номеров', 'order' => true])
            ->number('booking_count', ['text' => 'Количество броней'])
            ->enum('status', ['text' => 'Статус', 'order' => true, 'enum' => StatusEnum::class])
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
            'usersUrl' => $isUpdateAllowed ? route('hotels.users.create.dialog', ['hotel' => $this->model]) : null,

            'hotelLandmarkBaseRoute' => route('hotels.landmark.store', $this->model)
        ];
    }

    protected function prepareShowMenu($model)
    {
        Sidebar::submenu(new HotelMenu($model, 'info'));
    }

    protected function prepareGridQuery(Builder $query)
    {
        $query->with(['contracts'])->withRoomsCount();
    }

    protected function saving(array $data): array
    {
        $preparedData = $data;
        $cityId = $data['city_id'];
        $city = City::find($cityId);
        $from = new Point($city->center_lat, $city->center_lon);
        $to = Point::buildFromCoordinates($data['coordinates']);
        $preparedData['city_distance'] = $this->distanceCalculator->getDistance($from, $to);
        $this->validateZipCode($data['zipcode']);

        return $preparedData;
    }

    private function validateZipCode(?string $zipCode): void
    {
        if (empty($zipCode)) {
            return;
        }
        if (!is_numeric($zipCode) || strlen($zipCode) > 6) {
            throw new InvalidZipcode();
        }
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
            ->enum('visibility', ['label' => __('label.visibility'), 'emptyItem' => '', 'enum' => VisibilityEnum::class]
            )
            ->rating('rating', ['label' => __('label.rating'), 'emptyItem' => ''])
            ->checkbox('is_traveline_integration_enabled', ['label' => 'Traveline']);
    }

    private function hotelParams($model): ParamsTable
    {
        return (new ParamsTable())
            ->id('id', 'ID')
            ->text('supplier_name', 'Поставщик')
            ->text('name', 'Наименование')
            ->text('type_name', 'Категория')
            ->enum('visibility', 'Видимость', VisibilityEnum::class)
            ->enum('status', 'Статус', StatusEnum::class)
            ->custom('rating', 'Рейтинг', fn($v) => (new HotelRating($v))->render())
            ->custom('country_name', 'Страна', fn($v, $o) => "{$o['country_name']} / {$o['city_name']}")
            ->text('address', 'Адрес')
            ->text('zipcode', 'Индекс')
            ->date('created_at', 'Создан', ['format' => 'datetime'])
            ->custom('is_traveline_integration_enabled', 'Traveline', fn() => '<span class="traveline-badge">TL</span>')
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

    private function getLandmarkGrid(): GridContract
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
