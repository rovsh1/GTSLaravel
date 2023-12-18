<?php

namespace App\Hotel\Http\Controllers;

use App\Admin\Http\Requests\Booking\Hotel\UpdateExternalNumberRequest;
use App\Admin\Http\Resources\Room as RoomResource;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Room;
use App\Admin\Models\Reference\Currency;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\BookingAdapter;
use App\Admin\Support\Facades\Booking\Hotel\DetailsAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Hotel\Models\Booking;
use App\Hotel\Models\User;
use App\Hotel\Support\Facades\Layout;
use App\Hotel\Support\Http\AbstractController;
use App\Hotel\Support\View\LayoutBuilder as LayoutContract;
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Module\Booking\Requesting\Domain\Service\RequestingRules;
use Sdk\Booking\Enum\QuotaProcessingMethodEnum;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\SourceEnum;
use Sdk\Shared\Exception\ApplicationException;

class BookingController extends AbstractController
{
    public function index(): LayoutContract
    {
        $grid = $this->gridFactory();
        $query = $this->prepareGridQuery(Booking::query(), $grid->getSearchCriteria());
        $grid->data($query);

        return Layout::title($this->getPageHeader())
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => null,
            ]);
    }

    public function show(int $id): LayoutContract
    {
        $booking = BookingAdapter::getBooking($id);
        $order = OrderAdapter::getOrder($booking->orderId);
        $hotelId = $booking->details->hotelInfo->id;
        $client = Client::find($order->clientId);
        $hotel = Hotel::find($hotelId);

        $title = "Бронь №{$id}";
        Breadcrumb::prototype($this->prototype)
            ->add($title);

        return Layout::title($title)
            ->view($this->prototype->view('show'), [
                'bookingId' => $id,
                'hotelId' => $hotelId,
                'hotel' => $hotel,
                'model' => $booking,
                'cancelConditions' => $booking->cancelConditions,
                'client' => $client,
                'order' => $order,
                'currencies' => Currency::get(),
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($booking->creatorId),
                'timelineUrl' => $this->route('timeline', $id),
                'editUrl' => $this->isAllowed('update') ? $this->route('edit', $id) : null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->route('destroy', $id) : null,
                'hotelRooms' => RoomResource::collection(Room::whereHotelId($hotelId)->get())
            ]);
    }

    public function edit(int $id): LayoutContract
    {
        $breadcrumbs = Breadcrumb::prototype($this->prototype);

        $booking = BookingAdapter::getBooking($id);

        $title = "Бронь №{$id}";
        $breadcrumbs->addUrl($this->prototype->route('show', $id), $title);
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory(true)
            ->method('put')
            ->action($this->prototype->route('update', $id))
            ->data($this->prepareFormData($booking));

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'model' => $booking,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('show', $id),
//                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        $form = $this->formFactory(true)
            ->method('put')
            ->failUrl($this->prototype->route('edit', $id));

        $form->submitOrFail();

        $data = $form->getData();
        try {
            DetailsAdapter::update(
                bookingId: $id,
                period: $data['period'],
                note: $data['note'] ?? null
            );
            $this->administratorRepository->update($id, $data['manager_id'] ?? request()->user()->id);
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect($this->prototype->route('show', $id));
    }

    public function destroy(int $id): AjaxResponseInterface
    {
        BookingAdapter::deleteBooking($id);

        return new AjaxRedirectResponse($this->prototype->route());
    }

    public function copy(int $id): RedirectResponse
    {
        $newBooking = BookingAdapter::copyBooking($id);

        return redirect(
            $this->prototype->route('show', $newBooking->id)
        );
    }

    public function updateExternalNumber(UpdateExternalNumberRequest $request, int $id): AjaxResponseInterface
    {
        DetailsAdapter::updateExternalNumber($id, $request->getType(), $request->getNumber());

        return new AjaxSuccessResponse();
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->id('id', [
                'text' => '№',
                'order' => true,
//                'renderer' => function ($row, $val) {
//                    $bookingUrl = route($this->prototype->routeName('show'), $row['id']);
//                    $idLink = "<a href='{$bookingUrl}'>{$row['id']}</a>";
//                    $orderId = $row['order_id'];
//                    $orderUrl = route('booking-order.show', $orderId);
//                    $orderLink = "<a href='{$orderUrl}'>{$orderId}</a>";
//
//                    return "$idLink / {$orderLink}";
//                }
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

    private function searchForm()
    {
        return (new SearchForm())
            ->hidden('hotel_room_id', ['label' => 'Тип номера'])
            ->select('status', ['label' => 'Статус', 'items' => BookingAdapter::getStatuses(), 'emptyItem' => ''])
            ->dateRange('start_period', ['label' => 'Дата заезда'])
            ->dateRange('end_period', ['label' => 'Дата выезда'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    private function prepareFormData(object $booking): array
    {
        $hotelId = $booking->details->hotelInfo->id;
        $cityId = Hotel::find($hotelId)->city_id;
        $order = OrderAdapter::getOrder($booking->orderId);
        $manager = $this->administratorRepository->get($booking->id);
        $details = $booking->details;

        return [
            'quota_processing_method' => $details->quotaProcessingMethod->value,
            'manager_id' => $manager->id,
            'order_id' => $booking->orderId,
            'currency' => $order->clientPrice->currency->value,
            'hotel_id' => $hotelId,
            'city_id' => $cityId,
            'client_id' => $order->clientId,
            'legal_id' => $order->legalId,
            'period' => new CarbonPeriod($details->period->dateFrom, $details->period->dateTo),
            'note' => $booking->note,
        ];
    }

    protected function formFactory(bool $isEdit = false): FormContract
    {
        return Form::name('data')
            ->radio('quota_processing_method', [
                'label' => 'Тип брони',
                'emptyItem' => '',
                'required' => !$isEdit,
                'disabled' => $isEdit,
                'items' => [
                    ['id' => QuotaProcessingMethodEnum::REQUEST->value, 'name' => 'По запросу'],
                    ['id' => QuotaProcessingMethodEnum::QUOTA->value, 'name' => 'По квоте'],
                ]
            ])
            ->select('client_id', [
                'label' => __('label.client'),
                'required' => !$isEdit,
                'emptyItem' => '',
                'items' => Client::orderBy('name')->get(),
                'disabled' => $isEdit,
            ])
            ->hidden('order_id', [
                'label' => 'Заказ',
                'disabled' => $isEdit
            ])
            ->hidden('legal_id', [
                'label' => 'Юр. лицо',
            ])
            ->currency('currency', [
                'label' => 'Валюта',
                'emptyItem' => '',
            ])
            ->city('city_id', [
                'label' => __('label.city'),
                'emptyItem' => '',
                'required' => !$isEdit,
                'onlyWithHotels' => true,
                'disabled' => $isEdit
            ])
            ->hidden('hotel_id', [
                'label' => 'Отель',
                'required' => !$isEdit,
                'emptyItem' => '',
                'disabled' => $isEdit,
            ])
            ->manager('manager_id', [
                'label' => 'Менеджер',
                'emptyItem' => '',
                'value' => request()->user()->id,
            ])
            ->dateRange('period', [
                'label' => 'Дата заезда/выезда',
                'required' => true
            ])
            ->textarea('note', ['label' => 'Примечание']);
    }

    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    protected function route(string $name, mixed $parameters = []): string
    {
        return route("hotel-booking.{$name}", $parameters);
    }

    private function prepareGridQuery(Builder $query, array $searchCriteria): Builder
    {
        $requestableStatuses = array_map(fn(StatusEnum $status) => $status->value,
            RequestingRules::getRequestableStatuses());

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
                DB::raw('(SELECT bookings.status IN (' . implode(',', $requestableStatuses) . ')) as is_requestable'),
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );
    }

    private function getPageHeader(): ?string
    {
        /** @var User $user */
        $user = Auth::guard('hotel')->user();
        $hotel = \App\Hotel\Models\Hotel::find($user->hotel_id);

        return $hotel?->name;
    }
}
