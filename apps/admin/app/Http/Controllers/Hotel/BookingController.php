<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\BookingAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Pkg\Booking\Requesting\Domain\Service\RequestingRules;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\SourceEnum;

class BookingController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('hotel-booking');
    }

    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $grid = $this->gridFactory($hotel->id);
        $query = $this->prepareGridQuery(Booking::query(), $hotel->id, $grid->getSearchCriteria());
        $grid->data($query);

        return Layout::title($this->prototype->title('index'))
            ->view($this->prototype->view('index') ?? $this->prototype->view('grid') ?? 'default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
            ]);
    }

    protected function gridFactory(int $hotelId): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm($hotelId))
            ->checkbox('checked', ['checkboxClass' => 'js-select-booking', 'dataAttributeName' => 'booking-id'])
            ->travelineBadge('is_traveline_integration_enabled', ['text' => 'TL'])
            ->id('id', [
                'text' => '№',
                'order' => true,
                'renderer' => function ($row, $val) {
                    $bookingUrl = route($this->prototype->routeName('show'), $row['id']);
                    $idLink = "<a href='{$bookingUrl}'>{$row['id']}</a>";
                    $orderId = $row['order_id'];
                    $orderUrl = route('booking-order.show', $orderId);
                    $orderLink = "<a href='{$orderUrl}'>{$orderId}</a>";

                    return "$idLink / {$orderLink}";
                }
            ])
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => BookingAdapter::getStatuses(), 'order' => true]
            )
            ->text('client_name', ['text' => 'Клиент'])
            ->text('manager_name', ['text' => 'Менеджер'])
            ->text(
                'date_start',
                [
                    'text' => 'Заезд - выезд',
                    'renderer' => fn($row, $val) => \Format::period(new CarbonPeriod($val, $row['date_end']))
                ]
            )
            ->text(
                'city_name',
                ['text' => 'Город / Отель', 'renderer' => fn($row, $val) => "{$val} / {$row['hotel_name']}"]
            )
            ->textWithTooltip(
                'rooms_count',
                ['text' => 'Номера', 'tooltip' => fn($row, $val) => $this->getRoomNamesTooltip($row)]
            )
            ->text('guests_count', ['text' => 'Гостей'])
            ->enum('source', ['text' => 'Источник', 'order' => true, 'enum' => SourceEnum::class])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->text('actions', ['renderer' => fn($row, $val) => $this->getActionButtons($row)])
            ->orderBy('created_at', 'desc')
            ->paginator(20);
    }

    private function getRoomNamesTooltip(mixed $tableRow): string
    {
        $roomNames = $tableRow['room_names'] ?? null;
        if ($roomNames === null) {
            return '';
        }
        $roomNames = explode(',', $roomNames);

        return collect($roomNames)
            ->groupBy(fn(string $val) => trim($val))
            ->map(fn(Collection $values, $key) => "{$values->first()}: {$values->count()}")
            ->implode('<br>');
    }

    private function getActionButtons(mixed $tableRow): string
    {
        $buttons = '';
        $isRequestable = $tableRow['is_requestable'] ?? false;
        $hasDownloadableRequest = $tableRow['has_downloadable_request'] ?? false;
        if ($isRequestable) {
            $buttons .= '<a href="#" class="btn-request-send"><i class="icon">mail</i></a>';
        }
        if ($hasDownloadableRequest) {
            $buttons .= '<a href="#" class="btn-request-download"><i class="icon">download</i></a>';
        }
        if (strlen($buttons) === 0) {
            return '';
        }

        return "<div class='d-flex flex-row gap-2'>{$buttons}</div>";
    }

    private function searchForm(int $hotelId)
    {
        return (new SearchForm())
            ->number('order_id', ['label' => '№ Заказа'])
            ->select('hotel_room_id', ['label' => 'Тип номера', 'items' => Room::whereHotelId($hotelId)->get(), 'emptyItem' => ''])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => BookingAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
            ->numRange('guests_count', ['label' => 'Кол-во гостей'])
            ->dateRange('start_period', ['label' => 'Дата заезда'])
            ->dateRange('end_period', ['label' => 'Дата выезда'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    protected function prepareGridQuery(Builder $query, int $hotelId, array $searchCriteria): Builder
    {
        $requestableStatuses = array_map(fn(StatusEnum $status) => $status->value,
            RequestingRules::getRequestableStatuses());

        return $query
            ->applyCriteria($searchCriteria)
            ->where('booking_hotel_details.hotel_id', $hotelId)
            ->addSelect('bookings.*')
            ->join('orders', 'orders.id', '=', 'bookings.order_id')
            ->join('clients', 'clients.id', '=', 'orders.client_id')
            ->addSelect('clients.name as client_name')
            ->join('booking_hotel_details', 'bookings.id', '=', 'booking_hotel_details.booking_id')
            ->addSelect('booking_hotel_details.date_start as date_start')
            ->addSelect('booking_hotel_details.date_end as date_end')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->join('hotels', 'hotels.id', '=', 'booking_hotel_details.hotel_id')
            ->addSelect('hotels.name as hotel_name')
            ->join('r_cities', 'r_cities.id', '=', 'hotels.city_id')
            ->joinTranslatable('r_cities', 'name as city_name')
            ->join('administrator_bookings', 'administrator_bookings.booking_id', '=', 'bookings.id')
            ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->addSelect('administrators.presentation as manager_name')
            ->selectSub(
                DB::table('booking_hotel_room_guests')
                    ->selectRaw('count(1)')
                    ->whereExists(function ($query) {
                        $query->selectRaw(1)
                            ->from('booking_hotel_accommodations')
                            ->whereColumn('booking_hotel_accommodations.booking_id', 'bookings.id')
                            ->whereColumn(
                                'booking_hotel_room_guests.accommodation_id',
                                'booking_hotel_accommodations.id'
                            );
                    }),
                'guests_count'
            )
            ->addSelect(
                DB::raw(
                    '(SELECT 1 FROM traveline_hotels WHERE traveline_hotels.hotel_id = booking_hotel_details.hotel_id) AS is_traveline_integration_enabled'
                )
            )
            ->addSelect(
                DB::raw(
                    '(SELECT GROUP_CONCAT(room_name) FROM booking_hotel_accommodations WHERE booking_id=bookings.id) as room_names'
                )
            )
            ->addSelect(
                DB::raw(
                    '(SELECT COUNT(id) FROM booking_hotel_accommodations WHERE booking_id=bookings.id) as rooms_count'
                )
            )
            ->addSelect(
                DB::raw('(SELECT bookings.status IN (' . implode(',', $requestableStatuses) . ')) as is_requestable'),
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );
    }

    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.bookings.index', $hotel), 'Брони');

        Sidebar::submenu(new HotelMenu($hotel, 'bookings'));
    }
}
