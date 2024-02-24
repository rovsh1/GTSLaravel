<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
                $sheet->getCell($column->getColumnIndex() . $i)->getStyle()->applyFromArray($defaultCellStyle)->getFont(
                )->applyFromArray($this->defaultFont);
            }
            $sheet->getRowDimension($i)->setRowHeight(self::DEFAULT_ROW_HEIGHT);
        }
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

    protected function fillCellValue(string $coordinate, mixed $value): void
    {
        $this->spreadsheet->getActiveSheet()->getCell($coordinate)->setValue($value);
    }

    protected function fillValueByPlaceholder(string $placeholder, mixed $value): void
    {
        $coordinates = $this->searchCoordinatesOnPage($placeholder);
        $coordinate = current($coordinates);
        $this->fillCellValue($coordinate, $value);
    }

    protected function saveAs(string $filename): void
    {
        $writter = $this->getWritter();
        $writter->save($filename);
    }

    protected function searchCoordinatesOnPage(string $search): array
    {
        $foundInCells = [];
        foreach ($this->spreadsheet->getActiveSheet()->getRowIterator() as $row) {
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
