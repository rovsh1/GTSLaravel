<?php

namespace App\Admin\Http\Controllers\Report;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Order\Guest;
use App\Admin\Services\ReportCompiler\OrderReportCompiler;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Module\Booking\Shared\Infrastructure\Enum\StatusSettingsEntityEnum;
use Sdk\Booking\Enum\StatusEnum;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderReportCompiler $reportCompiler,
    ) {
    }

    public function index(): LayoutContract
    {
        $form = $this->formFactory()
            ->method('post')
            ->action(route('report-order.generate'));

        return Layout::title('Отчет по заказам')
            ->view('report.form.form', [
                'form' => $form,
                'submitText' => 'Сгенерировать отчет',
            ]);
    }

    public function generate(): BinaryFileResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl(route('report-order.index'));

        $form->submitOrFail();

        $data = $form->getData();
        /** @var CarbonPeriod $startPeriod */
        $startPeriod = $data['start_period'];
        $startPeriod = new CarbonPeriod($startPeriod->getStartDate(), $startPeriod->getEndDate()->setTime(23, 59, 59));
        /** @var CarbonPeriod $endPeriod */
        $endPeriod = $data['end_period'];
        $endPeriod = new CarbonPeriod($endPeriod->getStartDate(), $endPeriod->getEndDate()->setTime(23, 59, 59));
        /** @var array $clientIds */
        $clientIds = $data['client_ids'];
        /** @var array $managerIds */
        $managerIds = $data['manager_ids'];

        $bookings = Booking::query()
            ->addSelect('bookings.*')
            ->addSelect('orders.client_id')
            ->addSelect('orders.status as order_status')
            ->addSelect('orders.manual_client_penalty as order_manual_client_penalty')
            ->addSelect('orders.external_id as order_external_id')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->selectRaw(
                '(select COALESCE(SUM(sum), 0) from client_payment_landings where client_payment_landings.order_id = orders.id) as payed_amount'
            )
            ->selectRaw(
                "(SELECT title FROM supplier_services_translation WHERE language = 'ru' AND translatable_id = COALESCE(booking_airport_details.service_id, booking_other_details.service_id, booking_transfer_details.service_id)) as service_title"
            )
            ->selectRaw(
                "(SELECT name FROM hotels WHERE id = booking_hotel_details.hotel_id) as hotel_name"
            )
            ->selectRaw(
                "(SELECT presentation FROM administrators WHERE id = administrator_orders.administrator_id) as administrator_name"
            )
            ->selectRaw(
                '(SELECT name_ru FROM booking_status_settings WHERE status = orders.status AND entity_type = ' . StatusSettingsEntityEnum::ORDER->value . ') as order_status_name'
            )
            ->join('orders', 'orders.id', 'bookings.order_id')
            ->join('administrator_orders', 'administrator_orders.order_id', 'orders.id')
            ->leftJoin('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
            ->leftJoin('booking_other_details', 'booking_other_details.booking_id', 'bookings.id')
            ->leftJoin('booking_transfer_details', 'booking_transfer_details.booking_id', 'bookings.id')
            ->leftJoin('booking_airport_details', 'booking_airport_details.booking_id', 'bookings.id')
            ->whereIn('bookings.status', [StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE])
            ->where(function (Builder $builder) use ($clientIds) {
                if (!empty($clientIds)) {
                    $builder->whereIn('orders.client_id', $clientIds);
                }
            })
            ->where(function (Builder $builder) use ($managerIds) {
                if (!empty($managerIds)) {
                    $builder->whereIn('administrator_orders.administrator_id', $managerIds);
                }
            })
            ->where(function (Builder $builder) use ($startPeriod, $endPeriod) {
                $startPeriodCondition = [$startPeriod->getStartDate(), $startPeriod->getEndDate()];
                $endPeriodCondition = [$endPeriod->getStartDate(), $endPeriod->getEndDate()];
                $builder->whereBetween('booking_other_details.date', $endPeriodCondition)
                    ->orWhereBetween('booking_airport_details.date', $endPeriodCondition)
                    ->orWhere(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                        $query->whereBetween('booking_hotel_details.date_start', $startPeriodCondition)
                            ->whereBetween('booking_hotel_details.date_end', $endPeriodCondition);
                    })
                    ->orWhere(function (Builder $query) use ($endPeriodCondition) {
                        $query->whereBetween('booking_transfer_details.date_start', $endPeriodCondition)
                            ->whereNull('booking_transfer_details.date_end');
                    })
                    ->orWhere(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                        $query->whereBetween('booking_transfer_details.date_start', $startPeriodCondition)
                            ->whereBetween('booking_transfer_details.date_end', $endPeriodCondition);
                    });
            })
            ->get();

        $orderIds = $bookings->pluck('order_id')->unique()->toArray();
        $guests = [];
        if (count($orderIds) > 0) {
            $guests = Guest::whereIn('order_id', $orderIds)
                ->get()
                ->groupBy('order_id')
                ->map(
                    fn(Collection $guests) => $guests->map(
                        fn(Guest $guest) => "{$guest->name} ({$guest->country_name})"
                    )->all()
                );
        }

        $reportRowsGroupedByClient = $bookings->groupBy(['client_id', 'order_id'])->map(
            function (Collection $clients) use ($guests) {
                return $clients->map(function (Collection $bookings) use ($guests) {
                    $firstBooking = $bookings->first();
                    $orderData = [
                        'id' => $firstBooking->order_id,
                        'currency' => $firstBooking->client_currency->name,
                        'manager' => $firstBooking->administrator_name,
                        'payed_amount' => (float)$firstBooking->payed_amount,
                        'guests' => $guests->get($firstBooking->order_id),
                        'status' => $firstBooking->order_status_name,
                        'external_id' => $firstBooking->order_external_id,
                        'period' => '',
                        'service_amount' => 0,
                        'hotel_amount' => 0,
                        'total_amount' => 0,
                        'hotels' => [],
                        'services' => [],
                    ];
                    foreach ($bookings as $booking) {
                        $amount = $booking->client_penalty ?? $booking->client_manual_price ?? $booking->client_price;
                        $orderData['total_amount'] += $amount;
                        if ($booking->hotel_id !== null) {
                            $orderData['hotel_amount'] += $amount;
                            $hotelName = $booking->hotel_name;
                            if (!in_array($hotelName, $orderData['hotels'])) {
                                $orderData['hotels'][] = $hotelName;
                            }
                        } else {
                            $orderData['service_amount'] += $amount;
                            $serviceTitle = $booking->service_title;
                            if (!in_array($serviceTitle, $orderData['services'])) {
                                $orderData['services'][] = $serviceTitle;
                            }
                        }
                    }
                    if ($firstBooking->order_manual_client_penalty > 0) {
                        $orderData['total_amount'] = $firstBooking->order_manual_client_penalty;
                        $orderData['hotel_amount'] = 0;
                        $orderData['service_amount'] = 0;
                    }
                    $orderData['remaining_amount'] = $orderData['total_amount'] - $orderData['payed_amount'];

                    return $orderData;
                });
            }
        );

        $reportPeriod = new CarbonPeriod($startPeriod->getStartDate(), $endPeriod->getEndDate());
        $report = $this->reportCompiler->generate($reportRowsGroupedByClient->toArray(), $reportPeriod);
        $tempFileMetadata = stream_get_meta_data($report);
        $tempFilePath = Arr::get($tempFileMetadata, 'uri');

        return response()->download(
            $tempFilePath,
            uniqid('report_', 0) . '.xlsx',
        );
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->dateRange('start_period', ['label' => 'Дата заеда/начала', 'emptyItem' => '', 'required' => true])
            ->dateRange('end_period', ['label' => 'Дата выезда/завершения', 'emptyItem' => '', 'required' => true])
            ->client('client_ids', ['label' => 'Клиент', 'required' => true, 'multiple' => true])
            ->manager('manager_ids', ['label' => 'Менеджер', 'multiple' => true]);
    }
}
