<?php

declare(strict_types=1);

namespace App\Admin\Http\Controllers\Booking\Service;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Booking\BulkDeleteRequest;
use App\Admin\Http\Requests\Booking\UpdateManagerRequest;
use App\Admin\Http\Requests\Booking\UpdateNoteRequest;
use App\Admin\Http\Requests\Booking\UpdatePriceRequest;
use App\Admin\Http\Requests\Booking\UpdateStatusRequest;
use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Client\Client;
use App\Admin\Models\Reference\Currency;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Repositories\BookingAdministratorRepository;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Booking\BookingAdapter;
use App\Admin\Support\Facades\Booking\OrderAdapter;
use App\Admin\Support\Facades\Booking\PriceAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Pkg\Booking\Requesting\Domain\Service\RequestingRules;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Sdk\Shared\Enum\SourceEnum;
use Sdk\Shared\Exception\ApplicationException;

class BookingController extends Controller
{
    protected Prototype $prototype;

    public function __construct(
        private readonly BookingAdministratorRepository $administratorRepository
    ) {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();
        $data = $this->prepareGridQuery(Booking::withoutHotelBooking(), $grid->getSearchCriteria());
        $grid->data($data);

        return Layout::title('Брони услуг')
            ->view($this->prototype->view('index'), [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => route('service-booking.create')
            ]);
    }

    public function create(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype)
            ->add($this->prototype->title('create') ?? 'Новая запись');

        $form = $this->formFactory()
            ->method('post')
            ->action(route('service-booking.store'));

        return Layout::title('Новая бронь')
            ->view($this->prototype->view('form'), [
                'form' => $form,
                'cancelUrl' => route('service-booking.index')
            ]);
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl(route('service-booking.create'));

        $form->submitOrFail();

        $data = $form->getData();
        $creatorId = request()->user()->id;
        $managerId = $data['manager_id'] ?? $creatorId;
        $orderId = $data['order_id'] ?? null;
        $currency = $data['currency'] ? CurrencyEnum::from($data['currency']) : null;
        if ($orderId !== null && $currency === null) {
            $order = OrderAdapter::getOrder($orderId);
            $currency = CurrencyEnum::from($order->clientPrice->currency->value);
        }
        if ($currency === null) {
            $client = Client::find($data['client_id']);
            $currency = $client->currency;
        }
        try {
            $bookingId = BookingAdapter::createBooking(
                clientId: $data['client_id'],
                legalId: $data['legal_id'],
                currency: $currency,
                serviceId: $data['service_id'],
                managerId: $managerId,
                creatorId: $creatorId,
                orderId: $data['order_id'] ?? null,
                detailsData: $data,
                note: $data['note'] ?? null,
            );
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect(
            route('service-booking.show', $bookingId)
        );
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
            ->view($this->prototype->view('form'), [
                'model' => $booking,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('show', $id),
//                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null,
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
            BookingAdapter::updateNote($id, $data['note'] ?? null);
        } catch (ApplicationException $e) {
            $form->throwException($e);
        }

        return redirect($this->prototype->route('show', $id));
    }

    public function show(int $id): LayoutContract
    {
        $title = "Бронь №{$id}";

        $booking = BookingAdapter::getBooking($id);
        $order = OrderAdapter::getOrder($booking->orderId);
        $client = Client::find($order->clientId);

        Breadcrumb::prototype($this->prototype)
            ->add($title);

        return Layout::title($title)
            ->view($this->prototype->view('show'), [
                'bookingId' => $id,
                'model' => $booking,
                'client' => $client,
                'order' => $order,
                'supplier' => Supplier::find($booking->details->serviceInfo->supplierId),
                'cancelConditions' => $booking->cancelConditions,
                'isOtherServiceBooking' => $booking->serviceType->id === ServiceTypeEnum::OTHER_SERVICE->value,
                'currencies' => Currency::get(),
                'manager' => $this->administratorRepository->get($id),
                'creator' => Administrator::find($booking->creatorId),
                'editUrl' => null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route('destroy', $id) : null,
                'timelineUrl' => route('service-booking.timeline', $id),
            ]);
    }

    public function destroy(int $id): AjaxResponseInterface
    {
        BookingAdapter::deleteBooking($id);

        return new AjaxRedirectResponse($this->prototype->route());
    }

    public function get(int $id): JsonResponse
    {
        return response()->json(
            BookingAdapter::getBooking($id)
        );
    }

    public function getAvailableActions(int $id): JsonResponse
    {
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
        return response()->json(
            BookingAdapter::updateStatus(
                id: $id,
                status: $request->getStatus(),
                notConfirmedReason: $request->getNotConfirmedReason() ?? '',
                supplierPenalty: $request->getSupplierPenalty(),
                clientPenalty: $request->getClientPenalty(),
            )
        );
    }

    public function getStatusHistory(int $id): JsonResponse
    {
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

    public function bulkDelete(BulkDeleteRequest $request): AjaxResponseInterface
    {
        BookingAdapter::bulkDeleteBookings($request->getIds());

        return new AjaxSuccessResponse();
    }

    public function updatePrice(UpdatePriceRequest $request, int $id): AjaxResponseInterface
    {
        switch ($request->getAction()) {
            case UpdatePriceRequest::CLIENT_PRICE_ACTION:
                PriceAdapter::setManualClientPrice($id, $request->getClientPrice());
                break;
            case UpdatePriceRequest::SUPPLIER_PRICE_ACTION:
                PriceAdapter::setManualSupplierPrice($id, $request->getSupplierPrice());
                break;
            case UpdatePriceRequest::CLIENT_PENALTY_ACTION:
                PriceAdapter::setClientPenalty($id, $request->getGrossPenalty());
                break;
            case UpdatePriceRequest::SUPPLIER_PENALTY_ACTION:
                PriceAdapter::setSupplierPenalty($id, $request->getNetPenalty());
                break;
        }

        return new AjaxSuccessResponse();
    }

    public function recalculatePrices(int $id): AjaxResponseInterface
    {
        PriceAdapter::recalculatePrices($id);

        return new AjaxSuccessResponse();
    }

    public function updateNote(int $id, UpdateNoteRequest $request): AjaxResponseInterface
    {
        BookingAdapter::updateNote($id, $request->getNote());

        return new AjaxSuccessResponse();
    }

    public function updateManager(int $id, UpdateManagerRequest $request): AjaxResponseInterface
    {
        $this->administratorRepository->update($id, $request->getManagerId());

        return new AjaxSuccessResponse();
    }

    public function copy(int $id): RedirectResponse
    {
        $newBooking = BookingAdapter::copyBooking($id);

        $redirectUrl = $newBooking->serviceType->id === ServiceTypeEnum::HOTEL_BOOKING->value
            ? route('hotel-booking.show', $newBooking->id)
            : route('service-booking.show', $newBooking->id);

        return redirect($redirectUrl);
    }

    protected function formFactory(bool $isEdit = false): FormContract
    {
        return Form::name('data')
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
            ->bookingServiceType('service_type', [
                'label' => 'Тип услуги',
                'emptyItem' => '',
                'withoutHotel' => true,
                'required' => !$isEdit,
                'disabled' => $isEdit,
            ])
            ->select(
                'supplier_id',
                [
                    'label' => 'Поставщик',
                    'required' => !$isEdit,
                    'items' => Supplier::get(),
                    'emptyItem' => '',
                    'disabled' => $isEdit
                ]
            )
            ->hidden('service_id', ['label' => 'Услуга', 'required' => !$isEdit, 'disabled' => $isEdit])
            ->date('date', ['label' => 'Дата', 'required' => true])
            ->manager('manager_id', [
                'label' => 'Менеджер',
                'emptyItem' => '',
                'value' => request()->user()->id,
            ])
            ->textarea('note', ['label' => 'Примечание']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->checkbox('checked', ['checkboxClass' => 'js-select-booking', 'dataAttributeName' => 'booking-id'])
            ->id('id', [
                'text' => '№',
                'order' => true,
                'renderer' => function ($row, $val) {
                    $bookingUrl = route($this->prototype->routeName('show'), $row['id']);

                    return "<a href='{$bookingUrl}'>{$row['id']}</a>";
                }
            ])
            ->id('order_id', [
                'text' => '№ заказа',
                'order' => true,
                'renderer' => function ($row, $val) {
                    $orderUrl = route('booking-order.show', $row['order_id']);

                    return "<a href='{$orderUrl}'>{$row['order_id']}</a>";
                }
            ])
            ->bookingStatus(
                'status',
                ['text' => 'Статус', 'statuses' => BookingAdapter::getStatuses(), 'order' => true]
            )
            ->text('client_name', ['text' => 'Клиент'])
//            ->text('city_name', ['text' => 'Город'])
            ->enum('service_type', ['text' => 'Тип услуги', 'enum' => ServiceTypeEnum::class])
//            ->text('service_name', ['text' => 'Название услуги'])
//            ->date('date', ['text' => 'Дата прилёта/вылета'])
            ->enum('source', ['text' => 'Источник', 'order' => true, 'enum' => SourceEnum::class])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->text('actions', ['renderer' => fn($row, $val) => $this->getActionButtons($row)])
            ->orderBy('bookings.created_at', 'desc')
            ->paginator(20);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->number('order_id', ['label' => '№ Заказа'])
//            ->country('country_id', ['label' => 'Страна', 'default' => '1'])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => ''])
            ->bookingServiceType(
                'service_type',
                ['label' => 'Услуга', 'emptyItem' => '', 'enum' => ServiceTypeEnum::class, 'withoutHotel' => true]
            )
            ->select('manager_id', ['label' => 'Менеджер', 'items' => Administrator::all(), 'emptyItem' => ''])
            ->select('status', ['label' => 'Статус', 'items' => BookingAdapter::getStatuses(), 'emptyItem' => ''])
            ->enum('source', ['label' => 'Источник', 'enum' => SourceEnum::class, 'emptyItem' => ''])
//            ->dateRange('date_period', ['label' => 'Дата прилета/вылета'])
            ->dateRange('created_period', ['label' => 'Дата создания']);
    }

    private function prepareFormData(object $booking): array
    {
        $order = OrderAdapter::getOrder($booking->orderId);
        $manager = $this->administratorRepository->get($booking->id);

        return [
            'manager_id' => $manager->id,
            'order_id' => $booking->orderId,
            'currency' => $order->clientPrice->currency->value,
            'supplier_id' => $booking->details->serviceInfo->supplierId,
            'service_id' => $booking->details->serviceInfo->id,
            'service_type' => $booking->serviceType->id,
            'client_id' => $order->clientId,
            'legal_id' => $order->legalId,
            'date' => $booking->details->date ?? $booking->details->arrivalDate ?? $booking->details->departureDate ?? null,
            'note' => $booking->note,
        ];
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


    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    protected function getPrototypeKey(): string
    {
        return 'service-booking';
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
            ->join('administrator_bookings', 'administrator_bookings.booking_id', '=', 'bookings.id')
            ->join('administrators', 'administrators.id', '=', 'administrator_bookings.administrator_id')
            ->addSelect('administrators.presentation as manager_name')
            ->addSelect(
                DB::raw('(SELECT bookings.status IN (' . implode(',', $requestableStatuses) . ')) as is_requestable'),
            )
            ->addSelect(
                DB::raw(
                    'EXISTS(SELECT 1 FROM booking_requests WHERE bookings.id = booking_requests.booking_id AND is_archive = 0) as has_downloadable_request'
                ),
            );
    }
}
