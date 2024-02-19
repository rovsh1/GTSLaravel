<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler;

use App\Admin\Models\Client\Client;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrderReportCompiler extends AbstractReportCompiler
{
    private readonly string $templatePath;

    private array $defaultFont = [
        'name' => 'Arial',
        'size' => 12,
        'color' => ['rgb' => '000000'],
        'bold' => false,
        'italic' => false,
    ];

    private array $reportTotalData = [];

    public function __construct()
    {
        $this->templatePath = resource_path('report-templates/orders.xlsx');
        $reader = IOFactory::createReaderForFile($this->templatePath);
        $this->spreadsheet = $reader->load($this->templatePath);
    }

    /**
     * @param array $data
     * @return resource
     */
    public function generate(array $data, CarbonPeriod $period): mixed
    {
        $this->setCreatedAtDate(now());
        $this->setReportPeriod($period);
        $this->setManager('Manager');

        foreach ($data as $clientId => $orders) {
            $client = Client::find($clientId);
            $this->appendClientRows($client, $orders);
        }

        $this->appendReportTotal();

        $writer = $this->getWritter();
        $tempFile = tmpfile();
        $writer->save($tempFile);

        return $tempFile;
    }

    private function setCreatedAtDate(\DateTimeInterface $date): void
    {
        $this->fillValueByPlaceholder('{createdAt}', $date->format('d/m/y H:i'));
    }

    private function setReportPeriod(CarbonPeriod $period): void
    {
        $this->fillValueByPlaceholder(
            '{reportPeriod}',
            $period->getStartDate()->format('d.m.Y') . ' - ' . $period->getEndDate()->format('d.m.Y')
        );
    }

    private function setManager(string $manager): void
    {
        $this->fillValueByPlaceholder('{manager}', $manager);
    }

    private function appendClientRows(Client $client, array $rows): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
        $sheet->insertNewRowBefore($lastRow, 3);
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
        $sheet->getCell([2, $currentRow])->setValue('USD')->getStyle()->applyFromArray($cellStyle)->getFont(
        )->applyFromArray($headerValueFont);
        $currentRow++;

        if (count($rows) > 0) {
            $sheet->insertNewRowBefore($sheet->getHighestRow(), 2);
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
        foreach ($rows as $index => $row) {
            $bgColor = ($index === 0 || $index % 2 === 0) ? 'DEEAF6' : 'FFFFFF';
            $cellStyle = $this->getCellStyle($bgColor);

            $guestsCount = count($row['guests']);
            $guestsTotal += $guestsCount;
            $hotelsTotal += $row['hotel_amount'];
            $servicesTotal += $row['service_amount'];
            $amountTotal += $row['total_amount'];
            $payedTotal += $row['payed_amount'];
            $remainingTotal += $row['remaining_amount'];

            $sheet->insertNewRowBefore($sheet->getHighestRow());
            $sheet->getCell('A' . $currentRow)->setValue($row['id'])->getStyle()->applyFromArray($cellStyle)->getFont(
            )->applyFromArray($this->defaultFont);
            $sheet->getCell('B' . $currentRow)->setValue($row['status'])->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('C' . $currentRow)->setValue($row['external_id'])->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('D' . $currentRow)->setValue($row['period'])->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('E' . $currentRow)->setValue(implode("\n", $row['guests']))->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('F' . $currentRow)->setValue($guestsCount)->getStyle()->applyFromArray($cellStyle)->getFont(
            )->applyFromArray($this->defaultFont);
            $sheet->getCell('G' . $currentRow)->setValue(implode("\n", $row['hotels']))->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('H' . $currentRow)->setValue($row['hotel_amount'] . ' ' . $row['currency'])->getStyle(
            )->applyFromArray($cellStyle)->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('I' . $currentRow)->setValue(implode("\n", $row['services']))->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('J' . $currentRow)->setValue($row['service_amount'] . ' ' . $row['currency'])->getStyle(
            )->applyFromArray($cellStyle)->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('K' . $currentRow)->setValue($row['total_amount'] . ' ' . $row['currency'])->getStyle(
            )->applyFromArray($cellStyle)->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('L' . $currentRow)->setValue($row['payed_amount'] . ' ' . $row['currency'])->getStyle(
            )->applyFromArray($cellStyle)->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('M' . $currentRow)->setValue($row['remaining_amount'] . ' ' . $row['currency'])->getStyle(
            )->applyFromArray($cellStyle)->getFont()->applyFromArray($this->defaultFont);
            $sheet->getCell('N' . $currentRow)->setValue($row['manager'])->getStyle()->applyFromArray(
                $cellStyle
            )->getFont()->applyFromArray($this->defaultFont);

            $currentRow++;
        }

        $sheet->insertNewRowBefore($sheet->getHighestRow());
        $totalRow = [
            'Итого',
            '',
            '',
            '',
            '',
            $guestsTotal,
            '',
            $hotelsTotal . ' ' . $row['currency'],
            '',
            $servicesTotal . ' ' . $row['currency'],
            $amountTotal . ' ' . $row['currency'],
            $payedTotal . ' ' . $row['currency'],
            $remainingTotal . ' ' . $row['currency'],
            ''
        ];
        $cellStyle = $this->getCellStyle();
        foreach ($totalRow as $index => $value) {
            $sheet->getCell([++$index, $currentRow])
                ->setValue($value)
                ->getStyle()->applyFromArray($cellStyle)
                ->getFont()->applyFromArray($this->defaultFont);
        }

        $this->updateReportTotalData('hotel', $row['currency'], $hotelsTotal);
        $this->updateReportTotalData('service', $row['currency'], $servicesTotal);
        $this->updateReportTotalData('total', $row['currency'], $amountTotal);
        $this->updateReportTotalData('payed', $row['currency'], $payedTotal);
        $this->updateReportTotalData('remaining', $row['currency'], $remainingTotal);
    }

    private function appendReportTotal(): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $lastRow = $sheet->getHighestRow();
        $sheet->insertNewRowBefore($lastRow, 3);
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
        $totalCellStyle = $this->getCellStyle('FEFF03');
        $payedCellStyle = $this->getCellStyle('66FF67');
        $remainingCellFont = [...$boldFont, 'color' => ['rgb' => 'FF0000']];
        foreach ($this->reportTotalData as $currency => $totalAmounts) {
            $sheet->insertNewRowBefore($sheet->getHighestRow());
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
        $firstRow = $currentRow - count($this->reportTotalData) + 1;
        $alignmentStyle = [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ];
        $style = $sheet->getCell("A{$firstRow}")->setValue('ИТОГИ')->getStyle()->applyFromArray($defaultCellStyle);
        $style->getAlignment()->applyFromArray($alignmentStyle);
        $style->getFont()->applyFromArray([...$boldFont, 'size' => 22]);
        $sheet->mergeCells("A{$firstRow}:F{$currentRow}");
    }

    private function getCellStyle(string $bgColor = 'FFFFFF', bool $withBorders = false): array
    {
        $style = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgColor],
            ],
        ];
        if ($withBorders) {
            $style['borders'] = [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ];
        }

        return $style;
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
