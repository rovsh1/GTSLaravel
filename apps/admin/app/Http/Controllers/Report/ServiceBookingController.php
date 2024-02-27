<?php

namespace App\Admin\Http\Controllers\Report;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Supplier\Supplier;
use App\Admin\Services\ReportCompiler\ServiceBookingReportCompiler;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ServiceBookingController extends Controller
{
    public function __construct(
        private readonly ServiceBookingReportCompiler $reportCompiler,
    ) {
    }

    public function index(): LayoutContract
    {
        $form = $this->formFactory()
            ->method('post')
            ->action(route('report-service-booking.generate'));

        return Layout::title('Отчет по броням услуг')
            ->view('report.form.form', [
                'form' => $form,
                'submitText' => 'Сгенерировать отчет',
            ]);
    }

    public function generate(): BinaryFileResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl(route('report-service-booking.index'));

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
        /** @var array $supplierIds */
        $supplierIds = $data['supplier_ids'];
        /** @var array $serviceTypes */
        $serviceTypes = $data['service_types'];
        /** @var array $managerIds */
        $managerIds = $data['manager_ids'];

        $report = $this->reportCompiler->generate(
            request()->user(),
            'Отчет по броням - услуги',
            $endPeriod,
            $supplierIds,
            $startPeriod,
            $serviceTypes,
            $managerIds
        );
        $tempFileMetadata = stream_get_meta_data($report);
        $tempFilePath = Arr::get($tempFileMetadata, 'uri');

        return response()->download(
            $tempFilePath,
            uniqid('report_', false) . '.xlsx',
        );
    }

    protected function formFactory(): FormContract
    {
        return Form::name('data')
            ->dateRange('end_period', ['label' => 'Дата завершения', 'emptyItem' => '', 'required' => true])
            ->select(
                'supplier_ids',
                ['label' => 'Поставщик', 'required' => true, 'multiple' => true, 'items' => Supplier::all()]
            )
            ->bookingServiceType('service_types', [
                'label' => 'Тип услуги',
                'emptyItem' => '',
                'withoutHotel' => true,
                'multiple' => true,
            ])
            ->dateRange('start_period', ['label' => 'Дата начала', 'emptyItem' => ''])
            ->manager('manager_ids', ['label' => 'Менеджер', 'multiple' => true]);
    }
}
