<?php

namespace App\Admin\Http\Controllers\Report;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Order\Guest;
use App\Admin\Services\ReportCompiler\HotelReportCompiler;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sdk\Booking\Enum\StatusEnum;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookingController extends Controller
{
    public function __construct(
        private readonly HotelReportCompiler $reportCompiler,
    ) {
    }

    public function index(): LayoutContract
    {
        $form = $this->formFactory()
            ->method('post')
            ->action(route('report-booking.generate'));

        return Layout::title('Отчет по броням')
            ->view('report.form.form', [
                'form' => $form,
                'submitText' => 'Сгенерировать отчет',
            ]);
    }

    public function generate(): BinaryFileResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl(route('report-booking.index'));

        $form->submitOrFail();

        $data = $form->getData();
        /** @var CarbonPeriod $startPeriod */
        $startPeriod = $data['start_period'];
        if (!empty($startPeriod)) {
            $startPeriod = new CarbonPeriod(
                $startPeriod->getStartDate(),
                $startPeriod->getEndDate()->setTime(23, 59, 59)
            );
        }
        /** @var CarbonPeriod $endPeriod */
        $endPeriod = $data['end_period'];
        $endPeriod = new CarbonPeriod($endPeriod->getStartDate(), $endPeriod->getEndDate()->setTime(23, 59, 59));
        /** @var array $clientIds */
        $clientIds = $data['client_ids'];
        /** @var array $managerIds */
        $managerIds = $data['manager_ids'];

        $startPeriodCondition = !empty($startPeriod) ? [
            $startPeriod->getStartDate(),
            $startPeriod->getEndDate()
        ] : null;
        $endPeriodCondition = [$endPeriod->getStartDate(), $endPeriod->getEndDate()];

        $hotelBookingsQuery = Booking::query()
            ->addSelect('bookings.*')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->selectRaw(
                '(select COALESCE(SUM(sum), 0) from supplier_payment_landings where supplier_payment_landings.booking_id = bookings.id) as payed_amount'
            )
            ->selectRaw(
                "(SELECT name FROM hotels WHERE id = booking_hotel_details.hotel_id) as hotel_name"
            )
            ->addSelect('booking_hotel_details.date_start')
            ->addSelect('booking_hotel_details.date_end')
            ->join('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
            ->whereIn('bookings.status', [StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE])
            ->where(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                $query->whereBetween('booking_hotel_details.date_end', $endPeriodCondition);
                if (!empty($startPeriodCondition)) {
                    $query->whereBetween('booking_hotel_details.date_start', $startPeriodCondition);
                }
            });

        $hotelCostsData = DB::query()
            ->fromSub($hotelBookingsQuery, 'b')
            ->addSelect('b.hotel_id as hotel_id')
            ->selectRaw('COUNT(b.id) as bookings_count')
            ->selectRaw('SUM(b.payed_amount) as payed_amount')
            ->selectRaw('SUM(COALESCE(b.supplier_manual_price, b.supplier_price)) as hotel_supplier_price')
            ->selectRaw('MAX(b.hotel_name) as hotel_name')
            ->groupBy('b.hotel_id')
            ->get();

        $hotelIds = $hotelCostsData->pluck('hotel_id')->toArray();
        $hotels = [];
        if (count($hotelIds) > 0) {
            $hotels = Hotel::whereIn('hotels.id', $hotelIds)->get()->keyBy('id');
        }

        $hotelCostsData = $hotelCostsData->map(function (\stdClass $hotelData) use ($hotels) {
            $hotel = $hotels[$hotelData->hotel_id] ?? "Отель №{$hotelData->hotel_id}";
            $hotelData->city_name = $hotel->city_name;

            return $hotelData;
        });



        $bookingsData = DB::query()
            ->fromSub($hotelBookingsQuery, 'b')
            ->addSelect('b.id')
            ->addSelect('b.hotel_name')
            ->addSelect('b.date_start')
            ->addSelect('b.date_end')
            ->selectRaw(
                '(SELECT COUNT(id) FROM booking_hotel_accommodations WHERE booking_hotel_accommodations.booking_id = b.id) as rooms_count'
            )
            ->selectRaw('DATEDIFF(b.date_end, b.date_start) AS nights_count')
            ->get();

        $bookingIds = $bookingsData->pluck('id')->toArray();

        $guestsIndexedByBookingId = [];
        if (count($bookingIds) > 0) {
            $guestsIndexedByBookingId = Guest::query()
                ->addSelect('booking_hotel_accommodations.booking_id')
                ->join('booking_hotel_room_guests', 'booking_hotel_room_guests.guest_id', 'order_guests.id')
                ->join('booking_hotel_accommodations', 'booking_hotel_accommodations.id', 'booking_hotel_room_guests.accommodation_id')
                ->whereIn('booking_hotel_accommodations.booking_id', $bookingIds)
                ->get()
                ->groupBy('booking_id')
                ->map(
                    fn(Collection $guests) => $guests->map(
                        fn(Guest $guest) => "{$guest->name} ({$guest->country_name})"
                    )->all()
                );
        }

        $report = $this->reportCompiler->generate(
            request()->user(),
            'Отчет по броням',
            []
        );
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
            ->dateRange('end_period', ['label' => 'Дата выезда/завершения', 'emptyItem' => '', 'required' => true])
            ->client('client_ids', ['label' => 'Клиент', 'required' => true, 'multiple' => true])
            ->dateRange('start_period', ['label' => 'Дата заеда/начала', 'emptyItem' => ''])
            ->manager('manager_ids', ['label' => 'Менеджер', 'multiple' => true]);
    }
}
