<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

abstract class AbstractReportCompiler
{
    protected Spreadsheet $spreadsheet;

    public function getWritter(): IWriter
    {
        return IOFactory::createWriter($this->spreadsheet, 'Xlsx');
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
