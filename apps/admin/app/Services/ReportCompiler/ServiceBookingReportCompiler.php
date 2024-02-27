<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Services\ReportCompiler\Factory\ServiceBookingDataFactory;
use App\Admin\Support\Facades\Format;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ServiceBookingReportCompiler extends AbstractReportCompiler
{
    private readonly string $templatePath;

    public function __construct(
        private readonly ServiceBookingDataFactory $dataFactory,
    ) {
        $this->templatePath = resource_path('report-templates/base_template.xlsx');
        $reader = IOFactory::createReaderForFile($this->templatePath);
        $this->spreadsheet = $reader->load($this->templatePath);
    }

    /**
     * @param Administrator $administrator
     * @param array $data
     * @return resource
     */
    public function generate(
        Administrator $administrator,
        string $title,
        CarbonPeriod $endPeriod,
        array $supplierIds,
        ?CarbonPeriod $startPeriod = null,
        array $serviceTypes = [],
        array $managerIds = []
    ): mixed {
        $this->setCreatedAtDate(now());
        $this->setManager($administrator->presentation);
        $this->setReportPeriodLabel('Период броней:');
        $this->setLogo();
        $this->setSheetTitle($this->spreadsheet->getActiveSheet(), $title);

        [
            'bookings' => $bookings,
            'guestsIndexedByBookingId' => $guests,
            'serviceNames' => $serviceNames,
            'supplierNames' => $supplierNames
        ] = $this->dataFactory->build($endPeriod, $supplierIds, $startPeriod, $serviceTypes, $managerIds);

        $this->fillReportHeaderData($supplierNames, $serviceNames);
        $this->appendRows($bookings->toArray(), $guests->all());
        $this->fillReportPeriod();

        $writer = $this->getWritter();
        $tempFile = tmpfile();
        $writer->save($tempFile);

        return $tempFile;
    }

    private function fillReportHeaderData(array $supplierNames, array $serviceNames): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
        $this->insertNewRowBefore($lastRow, 3);
        $currentRow = $lastRow;

        $headerFont = [...$this->defaultFont, 'size' => 12, 'italic' => true, 'bold' => true];
        $headerValueFont = [...$headerFont, 'italic' => false, 'color' => ['rgb' => '0000FF']];
        $cellStyle = $this->getCellStyle();
        $sheet->getCell([1, $currentRow])->setValue('Выбранные услуги:')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($headerFont);
        $sheet->getCell([2, $currentRow])->setValue(implode(', ', $serviceNames))->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($headerValueFont);
        $currentRow++;

        if(count($supplierNames) <= 10){
            $supplierNames = implode(', ', $supplierNames);
        } else {
            $supplierNames = '';
        }
        $sheet->getCell([1, $currentRow])->setValue('Поставщик:')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($headerFont);
        $sheet->getCell([2, $currentRow])->setValue($supplierNames)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($headerValueFont);
    }

    private function appendRows(array $bookings, array $guestsIndexedByBookingId): void
    {
        if (count($bookings) === 0) {
            return;
        }
        $sheet = $this->spreadsheet->getActiveSheet();
        $currentRow = $sheet->getHighestRow();

        $this->insertNewRowBefore($sheet->getHighestRow(), 2);
        $currentRow++;
        $headerRow = [
            '№ Брони',
            'Клиент',
            'Услуга',
            'Поставщик',
            'Дата начала',
            'Дата завершения',
            'ФИО гостей',
            'Сумма НЕТТО',
        ];

        $style = $this->getCellStyle('5B9BD5', true);
        $boldFont = [...$this->defaultFont, 'bold' => true];

        foreach ($headerRow as $index => $value) {
            $sheet->getCell([++$index, $currentRow])
                ->setValue($value)
                ->getStyle()->applyFromArray($style)
                ->getFont()->applyFromArray($boldFont);
        }

        $totalAmount = 0;
        foreach ($bookings as $index => $booking) {
            $bookingStartDate = strtotime($booking['date_start']);
            $bookingEndDate = strtotime($booking['date_end']);
            if (!isset($this->reportPeriodStart)) {
                $this->reportPeriodStart = $bookingStartDate;
            } else {
                $this->reportPeriodStart = min($bookingStartDate, $this->reportPeriodStart);
            }

            if (!isset($this->reportPeriodEnd)) {
                $this->reportPeriodEnd = $bookingEndDate;
            } else {
                $this->reportPeriodEnd = max($bookingEndDate, $this->reportPeriodEnd);
            }

            $bgColor = ($index === 0 || $index % 2 === 0) ? 'DEEAF6' : 'FFFFFF';
            $cellStyle = $this->getCellStyle($bgColor);
            $this->insertNewRowBefore($sheet->getHighestRow());
            $currentRow++;

            $bookingId = $booking['id'];
            $guests = $guestsIndexedByBookingId[$bookingId] ?? [];
            $price = (int)($booking['manual_supplier_price'] ?? $booking['supplier_price']);
            $totalAmount += $price;
            $data = [
                'A' . $currentRow => $bookingId,
                'B' . $currentRow => $booking['client_name'],
                'C' . $currentRow => $booking['service_title'],
                'D' . $currentRow => $booking['supplier_name'],
                'E' . $currentRow => date('d/m/Y', $bookingStartDate),
                'F' . $currentRow => date('d/m/Y', $bookingEndDate),
                'G' . $currentRow => implode("\n", $guests),
                'H' . $currentRow => Format::price($price) . ' ' . $booking['supplier_currency'],
            ];

            foreach ($data as $coordinates => $value) {
                $sheet->getCell($coordinates)
                    ->setValue($value)
                    ->getStyle()
                    ->applyFromArray($cellStyle)
                    ->getFont()
                    ->applyFromArray($this->defaultFont);
            }

            $rowHeight = count($guests) > 1 ? count($guests) : 1;
            $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight * 15);
        }

        $this->insertNewRowBefore($sheet->getHighestRow());
        $sheet->getCell('A' . $currentRow)->setValue('Итого')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('B' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('C' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('D' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('E' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('F' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('G' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('H' . $currentRow)->setValue(Format::price($totalAmount) . ' ' . $booking['supplier_currency'])->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getRowDimension($currentRow)->setRowHeight(15);
    }
}
