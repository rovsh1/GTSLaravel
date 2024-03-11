<?php

namespace App\Admin\Http\Controllers\Report;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Booking\ReportsAdapter;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HotelBookingController extends Controller
{
    public function index(): LayoutContract
    {
        $form = $this->formFactory()
            ->method('post')
            ->action(route('report-hotel-booking.generate'));

        return Layout::title('Отчет по броням отелей')
            ->view('report.form.form', [
                'form' => $form,
                'submitText' => 'Сгенерировать отчет',
            ]);
    }

    public function generate(): BinaryFileResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl(route('report-hotel-booking.index'));

        $form->submitOrFail();

        $data = $form->getData();
        /** @var CarbonPeriod|null $startPeriod */
        $startPeriod = $data['start_period'];
        if (!empty($startPeriod)) {
            $startPeriod = new CarbonPeriod($startPeriod->getStartDate(), $startPeriod->getEndDate()->setTime(23, 59, 59));
        }
        /** @var CarbonPeriod $endPeriod */
        $endPeriod = $data['end_period'];
        $endPeriod = new CarbonPeriod($endPeriod->getStartDate(), $endPeriod->getEndDate()->setTime(23, 59, 59));
        /** @var array $managerIds */
        $managerIds = $data['manager_ids'];

        $report = ReportsAdapter::generateHotelBookingsReport(request()->user(), $endPeriod, $startPeriod, $managerIds);
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
            ->dateRange('end_period', ['label' => 'Дата выезда', 'emptyItem' => '', 'required' => true])
            ->dateRange('start_period', ['label' => 'Дата заеда', 'emptyItem' => ''])
            ->manager('manager_ids', ['label' => 'Менеджер', 'multiple' => true]);
    }
}
