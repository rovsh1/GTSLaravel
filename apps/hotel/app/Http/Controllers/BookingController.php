<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Http\Requests\Booking\SetNoCheckInRequest;
use App\Hotel\Http\Requests\Booking\UpdateExternalNumberRequest;
use App\Hotel\Http\Requests\Booking\UpdateNoteRequest;
use App\Hotel\Http\Requests\Booking\UpdatePenaltyRequest;
use App\Hotel\Http\Requests\Booking\UpdateStatusRequest;
use App\Hotel\Http\Resources\Room as RoomResource;
use App\Hotel\Models\Booking;
use App\Hotel\Models\Client;
use App\Hotel\Models\Hotel;
use App\Hotel\Models\Reference\Currency;
use App\Hotel\Models\Room;
use App\Hotel\Support\Facades\Booking\BookingAdapter;
use App\Hotel\Support\Facades\Booking\DetailsAdapter;
use App\Hotel\Support\Facades\Booking\OrderAdapter;
use App\Hotel\Support\Facades\Grid;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\View\Grid\GridBuilder as GridContract;
use App\Hotel\Support\View\Grid\SearchForm;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Pkg\Booking\EventSourcing\Application\UseCase\GetHistory;

class BookingController extends AbstractHotelController
{
    public function index(): LayoutContract
    {
        $grid = $this->gridFactory();
        $query = $this->prepareGridQuery(Booking::whereWaitingStatus()->whereByRequest(), $grid->getSearchCriteria());
        $query2 = $this->prepareGridQuery(Booking::whereWaitingStatus()->whereByQuota(), $grid->getSearchCriteria());
        $query3 = $this->prepareGridQuery(Booking::whereNotWaitingStatus(), $grid->getSearchCriteria());

        $grid->data($query);
        $grid2 = $this->gridFactory()->data($query2);
        $grid3 = $this->gridFactory()->data($query3);

        return Layout::title("Брони по запросу ({$query->count()})")
            ->view('booking.hotel.main.main', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'grid2' => $grid2,
                'title2' => "Брони по квоте ({$query2->count()})",
                'grid3' => $grid3,
                'title3' => "Брони ({$query3->count()})",
                'paginator' => $grid->getPaginator(),
                'createUrl' => null,
            ]);
    }

    public function show(int $id): LayoutContract
    {
        $this->checkRights($id);
        $booking = BookingAdapter::getBooking($id);
        $order = OrderAdapter::getOrder($booking->orderId);
        $hotelId = $booking->details->hotelInfo->id;
        $client = Client::find($order->clientId);
        $hotel = Hotel::find($hotelId);

        $title = "Бронь №{$id}";

        return Layout::title($title)
            ->view('booking.hotel.show.show', [
                'bookingId' => $id,
                'hotelId' => $hotelId,
                'hotel' => $hotel,
                'model' => $booking,
                'cancelConditions' => $booking->cancelConditions,
                'client' => $client,
                'order' => $order,
                'currencies' => Currency::get(),
                'timelineUrl' => route('booking.timeline', $id),
                'hotelRooms' => RoomResource::collection(Room::whereHotelId($hotelId)->get())
            ]);
    }

    public function get(int $id): JsonResponse
    {
        $this->checkRights($id);

        return response()->json(
            BookingAdapter::getBooking($id)
        );
    }

    public function getOrderGuests(int $id): JsonResponse
    {
        $this->checkRights($id);

        $booking = BookingAdapter::getBooking($id);
        $guests = OrderAdapter::getGuests($booking->orderId);

        return response()->json($guests);
    }

    public function getAvailableActions(int $id): JsonResponse
    {
        $this->checkRights($id);

        return response()->json(
            BookingAdapter::getAvailableActions($id)
        );
    }

    public function getStatuses(): JsonResponse
    {
        return response()->json(
            BookingAdapter::getStatuses()
        );
    }

    public function updateStatus(UpdateStatusRequest $request, int $id): JsonResponse
    {
        $this->checkRights($id);

        return response()->json(
            BookingAdapter::updateStatus(
                id: $id,
                status: $request->getStatus(),
                notConfirmedReason: $request->getNotConfirmedReason() ?? '',
                supplierPenalty: $request->getSupplierPenalty(),
            )
        );
    }

    public function setNoCheckIn(SetNoCheckInRequest $request, int $id): AjaxResponseInterface
    {
        $this->checkRights($id);

        BookingAdapter::setNoCheckIn($id, $request->getSupplierPenalty());

        return new AjaxSuccessResponse();
    }

    public function getStatusHistory(int $id): JsonResponse
    {
        $this->checkRights($id);

        return response()->json(
            array_map(fn($eventDto) => [
                'event' => $eventDto->description,
                'color' => $eventDto->color,
                'payload' => $eventDto->payload,
                'administratorName' => $eventDto->context['administrator']['name'] ?? null,
                'source' => $eventDto->context['source'] ?? null,
                'createdAt' => $eventDto->createdAt
            ], BookingAdapter::getStatusHistory($id))
        );
    }

    public function updateNote(int $id, UpdateNoteRequest $request): AjaxResponseInterface
    {
        $this->checkRights($id);

        BookingAdapter::updateNote($id, $request->getNote());

        return new AjaxSuccessResponse();
    }

    public function timeline(int $id): LayoutContract
    {
        $this->checkRights($id);

        $title = "Бронь №{$id}";

        return Layout::title($title)
            ->view('booking.hotel.timeline.timeline', [
                'history' => app(GetHistory::class)->execute($id)
            ]);
    }

    public function updateExternalNumber(UpdateExternalNumberRequest $request, int $id): AjaxResponseInterface
    {
        $this->checkRights($id);

        DetailsAdapter::updateExternalNumber($id, $request->getType(), $request->getNumber());

        return new AjaxSuccessResponse();
    }

    public function updatePenalty(UpdatePenaltyRequest $request, int $id): AjaxResponseInterface
    {
        $this->checkRights($id);

        BookingAdapter::setPenalty($id, $request->getPenalty());

        return new AjaxSuccessResponse();
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->bookingStatus('status', ['text' => 'Статус', 'statuses' => BookingAdapter::getStatuses(), 'order' => true]
            )
            ->id('id', [
                'text' => '№',
                'order' => true,
                'renderer' => function ($row, $val) {
                    $bookingUrl = route('booking.show', $row['id']);

                    return "<a href='{$bookingUrl}'>{$row['id']}</a>";
                }
            ])
            ->text('date_start', [
                'text' => 'Заезд - выезд',
                'renderer' => fn($row, $val) => \Format::period(new CarbonPeriod($val, $row['date_end']))
            ])
            ->textWithTooltip(
                'rooms_count',
                ['text' => 'Номера', 'tooltip' => fn($row, $val) => $this->getRoomNamesTooltip($row)]
            )
            ->text('guests_count', ['text' => 'Гостей'])
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

    private function searchForm()
    {
        return (new SearchForm())
            ->select(
                'hotel_room_id',
                [
                    'label' => 'Тип номера',
                    'items' => Room::whereHotelId($this->getHotel()->id)->get(),
                    'emptyItem' => ''
                ]
            )
            ->select('status', ['label' => 'Статус', 'items' => BookingAdapter::getStatuses(), 'emptyItem' => ''])
            ->dateRange('start_period', ['label' => 'Дата заезда'])
            ->dateRange('end_period', ['label' => 'Дата выезда'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    protected function route(string $name, mixed $parameters = []): string
    {
        return route("booking.{$name}", $parameters);
    }

    private function prepareGridQuery(Builder $query, array $searchCriteria): Builder
    {
        return $query
            ->applyCriteria($searchCriteria)
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
                    '(SELECT GROUP_CONCAT(room_name) FROM booking_hotel_accommodations WHERE booking_id=bookings.id) as room_names'
                )
            )
            ->addSelect(
                DB::raw(
                    '(SELECT COUNT(id) FROM booking_hotel_accommodations WHERE booking_id=bookings.id) as rooms_count'
                )
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );
    }

    private function checkRights(int $bookingId): void
    {
        $hasAccess = Booking::query()
            ->withoutGlobalScope('default')
            ->selectRaw(1)
            ->where('bookings.id', $bookingId)
            ->join('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
            ->where('booking_hotel_details.hotel_id', $this->getHotel()->id)
            ->exists();

        if (!$hasAccess) {
            abort(403);
        }
    }
}
