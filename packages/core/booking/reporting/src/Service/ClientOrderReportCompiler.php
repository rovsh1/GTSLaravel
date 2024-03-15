<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\Service;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Client\Client;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Pkg\Booking\Reporting\Service\Factory\ClientOrderDataFactory;

class ClientOrderReportCompiler extends AbstractReportCompiler
{
    private readonly string $templatePath;

    private array $reportTotalData = [];

    public function __construct(
        private readonly ClientOrderDataFactory $dataFactory,
    )
    {
        $this->templatePath = __DIR__ . '/../../resources/templates/report_template.xlsx';
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
        CarbonPeriod $endPeriod,
        ?CarbonPeriod $startPeriod = null,
        array $clientIds = [],
        array $managerIds = []
    ): mixed {
        $this->setCreatedAtDate(now());
        $this->setManager($administrator->presentation);
        $this->setReportPeriodLabel('Период заказов:');
        $this->setLogo();
        $this->setSheetTitle($this->spreadsheet->getActiveSheet(), 'Отчет по заказам');

        $ordersGroupedByClient = $this->dataFactory->build($endPeriod, $startPeriod, $clientIds, $managerIds);
        foreach ($ordersGroupedByClient as $clientId => $orders) {
            $client = Client::find($clientId);
            $this->appendClientRows($client, $orders);
        }

        $this->fillReportPeriod();
        $this->fillReportTotal();

        $writer = $this->getWritter();
        $tempFile = tmpfile();
        $writer->save($tempFile);

        return $tempFile;
    }

    private function appendClientRows(Client $client, array $rows): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
        $this->insertNewRowBefore($lastRow, 3);
        $currentRow = $lastRow + 1;

        $headerFont = [...$this->defaultFont, 'size' => 14];
        $headerValueFont = [...$headerFont, 'bold' => true];
        $cellStyle = $this->getCellStyle();
        $cellValueStyle = $this->getCellStyle('FEFF03');
        $sheet->getCell([1, $currentRow])->setValue('Клиент:')->getStyle()->applyFromArray($cellStyle)->getFont(
        )->applyFromArray($headerFont);
        $sheet->getCell([2, $currentRow])->setValue($client->name)->getStyle()->applyFromArray(
            $cellValueStyle
        )->getFont()->applyFromArray($headerValueFont);
        $currentRow++;
        $sheet->getCell([1, $currentRow])->setValue('Валюта:')->getStyle()->applyFromArray($cellStyle)->getFont(
        )->applyFromArray($headerFont);
        $sheet->getCell([2, $currentRow])->setValue($client->currency->name)->getStyle()->applyFromArray(
            $cellStyle
        )->getFont()->applyFromArray($headerValueFont);
        $currentRow++;

        if (count($rows) > 0) {
            $this->insertNewRowBefore($sheet->getHighestRow(), 2);
            $currentRow++;
            $headerRow = [
                '№ Заказа',
                'Статус',
                '№ заявки клиента',
                'Период',
                'ФИО гостей',
                'Кол-во гостей',
                'Отели',
                'Сумма за отели',
                'Услуги',
                'Сумма за услуги',
                'ИТОГО',
                'Оплачено',
                'Остаток к оплате',
                'Менеджер'
            ];

            $style = $this->getCellStyle('5B9BD5');
            $boldFont = [...$this->defaultFont, 'bold' => true];

            foreach ($headerRow as $index => $value) {
                $sheet->getCell([++$index, $currentRow])
                    ->setValue($value)
                    ->getStyle()->applyFromArray($style)
                    ->getFont()->applyFromArray($boldFont);
            }
        }
        $currentRow++;

        $guestsTotal = 0;
        $hotelsTotal = 0;
        $servicesTotal = 0;
        $amountTotal = 0;
        $payedTotal = 0;
        $remainingTotal = 0;
        $orderIndex = 0;
        foreach ($rows as $row) {
            $bgColor = ($orderIndex === 0 || $orderIndex % 2 === 0) ? 'DEEAF6' : 'FFFFFF';
            $cellStyle = $this->getCellStyle($bgColor);

            $guests = $row['guests'] ?? [];
            $guestsCount = count($guests);
            $guestsTotal += $guestsCount;
            $hotelsTotal += $row['hotel_amount'];
            $servicesTotal += $row['service_amount'];
            $amountTotal += $row['total_amount'];
            $payedTotal += $row['payed_amount'];
            $remainingTotal += $row['remaining_amount'];

            $this->insertNewRowBefore($sheet->getHighestRow());

            $data = [
                'A' . $currentRow => $row['id'],
                'B' . $currentRow => $row['status'],
                'C' . $currentRow => $row['external_id'],
                'D' . $currentRow => date('d.m.Y', $row['period_start']) . ' - ' . date('d.m.Y', $row['period_end']),
                'E' . $currentRow => implode("\n", $guests),
                'F' . $currentRow => count($guests),
                'G' . $currentRow => implode("\n", $row['hotels']),
                'H' . $currentRow => $row['hotel_amount'] . ' ' . $row['currency'],
                'I' . $currentRow => implode("\n", $row['services']),
                'J' . $currentRow => $row['service_amount'] . ' ' . $row['currency'],
                'K' . $currentRow => $row['total_amount'] . ' ' . $row['currency'],
                'L' . $currentRow => $row['payed_amount'] . ' ' . $row['currency'],
                'M' . $currentRow => $row['remaining_amount'] . ' ' . $row['currency'],
                'N' . $currentRow => $row['manager'],
            ];

            foreach ($data as $coordinates => $value) {
                $sheet->getCell($coordinates)
                    ->setValue($value)
                    ->getStyle()
                    ->applyFromArray($cellStyle)
                    ->getFont()
                    ->applyFromArray($this->defaultFont);
            }

            $rowHeight = max(count($row['hotels']), count($row['services']), count($guests));
            $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight * 15);

            if (!isset($this->reportPeriodStart)) {
                $this->reportPeriodStart = $row['period_start'];
            } else {
                $this->reportPeriodStart = min($row['period_start'], $this->reportPeriodStart);
            }

            if (!isset($this->reportPeriodEnd)) {
                $this->reportPeriodEnd = $row['period_end'];
            } else {
                $this->reportPeriodEnd = max($row['period_end'], $this->reportPeriodEnd);
            }

            $currentRow++;
            $orderIndex++;
        }

        $this->insertNewRowBefore($sheet->getHighestRow());
        $defaultCellStyle = $this->getCellStyle('FFFFFF', true);
        $totalCellStyle = $this->getCellStyle('FEFF03', true);
        $payedCellStyle = $this->getCellStyle('66FF67', true);
        $remainingCellFont = [...$boldFont, 'color' => ['rgb' => 'FF0000']];
        $sheet->getCell('A' . $currentRow)->setValue('Итого')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('B' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('C' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('D' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('E' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('F' . $currentRow)->setValue($guestsTotal)->getStyle()->applyFromArray(
            $defaultCellStyle
        )->getFont()->applyFromArray($boldFont);
        $sheet->getCell('G' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('H' . $currentRow)->setValue($hotelsTotal . ' ' . $row['currency'])->getStyle()->applyFromArray(
            $defaultCellStyle
        )->getFont()->applyFromArray($boldFont);
        $sheet->getCell('I' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getCell('J' . $currentRow)->setValue($servicesTotal . ' ' . $row['currency'])->getStyle(
        )->applyFromArray($defaultCellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('K' . $currentRow)->setValue($amountTotal . ' ' . $row['currency'])->getStyle()->applyFromArray(
            $totalCellStyle
        )->getFont()->applyFromArray($boldFont);
        $sheet->getCell('L' . $currentRow)->setValue($payedTotal . ' ' . $row['currency'])->getStyle()->applyFromArray(
            $payedCellStyle
        )->getFont()->applyFromArray($boldFont);
        $sheet->getCell('M' . $currentRow)->setValue($remainingTotal . ' ' . $row['currency'])->getStyle(
        )->applyFromArray($defaultCellStyle)->getFont()->applyFromArray($remainingCellFont);
        $sheet->getCell('N' . $currentRow)->setValue('')->getStyle()->applyFromArray($defaultCellStyle)->getFont(
        )->applyFromArray($boldFont);
        $sheet->getRowDimension($currentRow)->setRowHeight(15);

        $this->updateReportTotalData('hotel', $row['currency'], $hotelsTotal);
        $this->updateReportTotalData('service', $row['currency'], $servicesTotal);
        $this->updateReportTotalData('total', $row['currency'], $amountTotal);
        $this->updateReportTotalData('payed', $row['currency'], $payedTotal);
        $this->updateReportTotalData('remaining', $row['currency'], $remainingTotal);
    }

    private function fillReportTotal(): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
        $this->insertNewRowBefore($lastRow, 3);
        $currentRow = $lastRow + 1;

        $headerRow = [
            '',
            '',
            '',
            '',
            '',
            '',
            'ВАЛЮТА',
            'Сумма за отели',
            '',
            'Сумма за услуги',
            'ИТОГО',
            'Оплачено',
            'Остаток к оплате',
        ];

        $headerCellStyle = $this->getCellStyle('f3b183', true);
        $boldFont = [...$this->defaultFont, 'bold' => true];
        foreach ($headerRow as $index => $value) {
            $sheet->getCell([++$index, $currentRow])
                ->setValue($value)
                ->getStyle()->applyFromArray($headerCellStyle)
                ->getFont()->applyFromArray($boldFont);
        }

        $defaultCellStyle = $this->getCellStyle('FFFFFF', true);
        $totalCellStyle = $this->getCellStyle('FEFF03', true);
        $payedCellStyle = $this->getCellStyle('66FF67', true);
        $remainingCellFont = [...$boldFont, 'color' => ['rgb' => 'FF0000']];
        foreach ($this->reportTotalData as $currency => $totalAmounts) {
            $this->insertNewRowBefore($sheet->getHighestRow());
            $currentRow++;
            $sheet->getCell('G' . $currentRow)->setValue($currency)->getStyle()->applyFromArray(
                $defaultCellStyle
            )->getFont()->applyFromArray($boldFont);
            $sheet->getCell('H' . $currentRow)->setValue($totalAmounts['hotel_amount'] . ' ' . $currency)->getStyle(
            )->applyFromArray($defaultCellStyle)->getFont()->applyFromArray($boldFont);
            $sheet->getCell('J' . $currentRow)->setValue($totalAmounts['service_amount'] . ' ' . $currency)->getStyle(
            )->applyFromArray($defaultCellStyle)->getFont()->applyFromArray($boldFont);
            $sheet->getCell('K' . $currentRow)->setValue($totalAmounts['total_amount'] . ' ' . $currency)->getStyle(
            )->applyFromArray($totalCellStyle)->getFont()->applyFromArray($boldFont);
            $sheet->getCell('L' . $currentRow)->setValue($totalAmounts['payed_amount'] . ' ' . $currency)->getStyle(
            )->applyFromArray($payedCellStyle)->getFont()->applyFromArray($boldFont);
            $sheet->getCell('M' . $currentRow)->setValue($totalAmounts['remaining_amount'] . ' ' . $currency)->getStyle(
            )->applyFromArray($defaultCellStyle)->getFont()->applyFromArray($remainingCellFont);
        }
        $countCurrencies = count($this->reportTotalData);
        $firstRow = $currentRow - $countCurrencies + 1;
        $alignmentStyle = [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ];
        $style = $sheet->getCell("A{$firstRow}")->setValue('ИТОГИ')->getStyle()->applyFromArray($defaultCellStyle);
        $style->getAlignment()->applyFromArray($alignmentStyle);
        $style->getFont()->applyFromArray([...$boldFont, 'size' => 22]);
        $sheet->mergeCells("A{$firstRow}:F{$currentRow}");
        $rowHeight = $countCurrencies * 20 > 30 ? $countCurrencies * 20 : 30;
        $sheet->getRowDimension($firstRow)->setRowHeight($rowHeight);
    }

    /**
     * @param string $type
     * @param string $currency
     * @param float $amount
     * @return void
     */
    private function updateReportTotalData(string $type, string $currency, float $amount): void
    {
        $key = "{$type}_amount";
        if (!isset($this->reportTotalData[$currency][$key])) {
            $this->reportTotalData[$currency][$key] = 0;
        }
        $this->reportTotalData[$currency][$key] += $amount;
    }
}