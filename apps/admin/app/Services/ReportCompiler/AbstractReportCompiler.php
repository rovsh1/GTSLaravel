<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

abstract class AbstractReportCompiler
{
    protected const DEFAULT_ROW_HEIGHT = 14;

    protected Spreadsheet $spreadsheet;

    protected array $defaultFont = [
        'name' => 'Arial',
        'size' => 12,
        'color' => ['rgb' => '000000'],
        'bold' => false,
        'italic' => false,
    ];

    protected int $reportPeriodStart;

    protected int $reportPeriodEnd;

    public function getWritter(): IWriter
    {
        return IOFactory::createWriter($this->spreadsheet, 'Xlsx');
    }

    protected function insertNewRowBefore(int $before, int $numberOfRows = 1): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->insertNewRowBefore($before, $numberOfRows);

        $defaultCellStyle = $this->getCellStyle();
        for ($i = $before; $i <= $before + $numberOfRows; $i++) {
            foreach ($sheet->getColumnIterator() as $column) {
                $sheet->getCell($column->getColumnIndex() . $i)->getStyle()->applyFromArray($defaultCellStyle)->getFont()->applyFromArray($this->defaultFont);
            }
            $sheet->getRowDimension($i)->setRowHeight(self::DEFAULT_ROW_HEIGHT);
        }
    }


    protected function setCreatedAtDate(\DateTimeInterface $date): void
    {
        $this->fillValueByPlaceholder('{createdAt}', $date->format('d/m/y H:i'));
    }

    protected function setReportPeriodLabel(string $label): void
    {
        $this->fillValueByPlaceholder('{periodLabel}', $label);
    }

    protected function fillReportPeriod(): void
    {
        $period = '';
        if (isset($this->reportPeriodStart) && isset($this->reportPeriodEnd)) {
            $period = date('d.m.Y', $this->reportPeriodStart) . ' - ' . date('d.m.Y', $this->reportPeriodEnd);
        }
        $this->fillValueByPlaceholder('{reportPeriod}', $period);
    }

    protected function setManager(string $manager): void
    {
        $this->fillValueByPlaceholder('{manager}', $manager);
    }

    protected function setSheetTitle(Worksheet $sheet, string $title): void
    {
        $sheet->setTitle($title);
        $this->fillValueByPlaceholder('{sheetTitle}', mb_strtoupper($title), $sheet);
    }

    protected function setLogo(): void
    {
        $drawing = new Drawing();
        $drawing->setPath(storage_path('app/public/company-logo-small.png'));
        $drawing->setHeight(100);

        $drawing->setCoordinates('A1');
        $drawing->setOffsetY(15);
        $drawing->setOffsetX(15);

        $this->spreadsheet->getActiveSheet()
            ->getDrawingCollection()
            ->append($drawing);
    }

    protected function getCellStyle(string $bgColor = 'FFFFFF', bool $withBorders = false): array
    {
        $style = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
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

    protected function fillCellValue(string $coordinate, mixed $value, ?Worksheet $sheet = null): void
    {
        $worksheet = $sheet ?? $this->spreadsheet->getActiveSheet();
        $worksheet->getCell($coordinate)->setValue($value);
    }

    protected function fillValueByPlaceholder(string $placeholder, mixed $value, ?Worksheet $sheet = null): void
    {
        $worksheet = $sheet ?? $this->spreadsheet->getActiveSheet();
        $coordinates = $this->searchCoordinatesOnPage($placeholder, $worksheet);
        $coordinate = current($coordinates);
        $this->fillCellValue($coordinate, $value);
    }

    protected function saveAs(string $filename): void
    {
        $writter = $this->getWritter();
        $writter->save($filename);
    }

    protected function searchCoordinatesOnPage(string $search, ?Worksheet $sheet = null): array
    {
        $worksheet = $sheet ?? $this->spreadsheet->getActiveSheet();

        $foundInCells = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            foreach ($cellIterator as $cell) {
                if (empty($cell->getValue())) {
                    continue;
                }
                if ($cell->getValue() === $search) {
                    $foundInCells[] = $cell->getCoordinate();
                }
            }
        }

        return $foundInCells;
    }
}
