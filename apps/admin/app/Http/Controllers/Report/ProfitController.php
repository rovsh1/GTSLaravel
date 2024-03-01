<?php

namespace App\Admin\Http\Controllers\Report;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Services\ReportCompiler\ProfitReportCompiler;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfitController extends Controller
{
    public function __construct(
        private readonly ProfitReportCompiler $reportCompiler,
    ) {
    }

    public function index(): LayoutContract
    {
        $form = $this->formFactory()
            ->method('post')
            ->action(route('report-profit.generate'));

        return Layout::title('Отчет по прибыли')
            ->view('report.form.form', [
                'form' => $form,
                'submitText' => 'Сгенерировать отчет',
            ]);
    }

    public function generate(): BinaryFileResponse
    {
        $form = $this->formFactory()
            ->method('post')
            ->failUrl(route('report-profit.index'));

        $form->submitOrFail();

        $data = $form->getData();
        /** @var CarbonPeriod $startPeriod */
        $startPeriod = $data['start_period'];
        if (!empty($startPeriod)) {
            $startPeriod = new CarbonPeriod($startPeriod->getStartDate(), $startPeriod->getEndDate()->setTime(23, 59, 59));
        }
        /** @var CarbonPeriod $endPeriod */
        $endPeriod = $data['end_period'];
        $endPeriod = new CarbonPeriod($endPeriod->getStartDate(), $endPeriod->getEndDate()->setTime(23, 59, 59));

        $report = $this->reportCompiler->generate(request()->user(), $endPeriod, $startPeriod);
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
            ->dateRange('start_period', ['label' => 'Дата заеда/начала', 'emptyItem' => '']);
    }
}