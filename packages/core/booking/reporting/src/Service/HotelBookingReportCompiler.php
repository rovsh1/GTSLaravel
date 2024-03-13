<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\Service;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Facades\Format;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Pkg\Booking\Reporting\Service\Factory\HotelBookingDataFactory;
use Pkg\Booking\Reporting\Service\Factory\HotelCostsDataFactory;

class HotelBookingReportCompiler extends AbstractReportCompiler
{
    private readonly string $templatePath;

    public function __construct(
        private readonly HotelCostsDataFactory $costsDataFactory,
        private readonly HotelBookingDataFactory $bookingDataFactory,
    ) {
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
        array $managerIds = []
    ): mixed {
        $hotelBookingsReportSheet = clone $this->spreadsheet->getActiveSheet();
        $hotelBookingsReportSheet->setTitle('List2');
        $this->spreadsheet->addSheet($hotelBookingsReportSheet);

        $this->generateHotelCostsReport($administrator, 'Расходы по отелям', $endPeriod, $startPeriod, $managerIds);

        $this->spreadsheet->setActiveSheetIndex($this->spreadsheet->getActiveSheetIndex() + 1);
        $this->generateHotelBookingReport($administrator, 'Отчёт по броням - ОТЕЛИ', $endPeriod, $startPeriod, $managerIds);

        $writer = $this->getWritter();
        $tempFile = tmpfile();
        $writer->save($tempFile);

        return $tempFile;
    }

    private function generateHotelCostsReport(
        Administrator $administrator,
        string $title,
        CarbonPeriod $endPeriod,
        ?CarbonPeriod $startPeriod = null,
        array $managerIds = []
    ) {
        $this->setCreatedAtDate(now());
        $this->setManager($administrator->presentation);
        $this->setReportPeriodLabel('Период:');
        $this->setLogo();
        $this->setSheetTitle($this->spreadsheet->getActiveSheet(), $title);

        $costsRows = $this->costsDataFactory->build($endPeriod, $startPeriod, $managerIds);
        $this->appendCostsRows($costsRows);

        $this->fillReportPeriod();
    }

    private function generateHotelBookingReport(
        Administrator $administrator,
        string $title,
        CarbonPeriod $endPeriod,
        ?CarbonPeriod $startPeriod = null,
        array $managerIds = []
    ) {
        $this->setCreatedAtDate(now());
        $this->setManager($administrator->presentation);
        $this->setReportPeriodLabel('Период броней:');
        $this->setLogo();
        $this->setSheetTitle($this->spreadsheet->getActiveSheet(), $title);

        [
            'bookings' => $bookings,
            'guestsIndexedByBookingId' => $guests,
        ] = $this->bookingDataFactory->build($endPeriod, $startPeriod, $managerIds);
        $this->appendBookingsRows($bookings, $guests);

        $this->fillReportPeriod();
    }

    private function appendCostsRows(array $rows): void
    {
        if (count($rows) === 0) {
            return;
        }
        $sheet = $this->spreadsheet->getActiveSheet();
        $currentRow = $sheet->getHighestRow();

        $this->insertNewRowBefore($sheet->getHighestRow(), 2);
        $currentRow++;
        $headerRow = [
            'Отель',
            'Город',
            'Итого оборот',
            'Выплачено',
            'Остаток к оплате',
            'Кол-во броней',
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
        $totalPayedAmount = 0;
        $totalRemainingAmount = 0;
        $totalCountBookings = 0;
        foreach ($rows as $index => $row) {
            $bgColor = ($index === 0 || $index % 2 === 0) ? 'FFFFFF' : 'DEEAF6';
            $cellStyle = $this->getCellStyle($bgColor);
            $this->insertNewRowBefore($sheet->getHighestRow());
            $currentRow++;

            $price = (int)$row->hotel_supplier_price;
            $payedAmount = (int)$row->payed_amount;
            $remainingAmount = $price - $payedAmount;
            $countBookings = (int)$row->bookings_count;
            $totalAmount += $price;
            $totalPayedAmount += $payedAmount;
            $totalRemainingAmount += $remainingAmount;
            $totalCountBookings += $countBookings;
            $data = [
                'A' . $currentRow => $row->hotel_name,
                'B' . $currentRow => $row->city_name,
                'C' . $currentRow => Format::number($price) . ' ' . $row->currency,
                'D' . $currentRow => Format::number($payedAmount) . ' ' . $row->currency,
                'E' . $currentRow => Format::number($remainingAmount) . ' ' . $row->currency,
                'F' . $currentRow => $countBookings,
            ];

            foreach ($data as $coordinates => $value) {
                $sheet->getCell($coordinates)
                    ->setValue($value)
                    ->getStyle()
                    ->applyFromArray($cellStyle)
                    ->getFont()
                    ->applyFromArray($this->defaultFont);
            }
        }

        $cellStyle = $this->getCellStyle('FFFFFF', true);
        $remainingCellFont = [...$boldFont, 'color' => ['rgb' => 'FF0000']];
        $this->insertNewRowBefore($sheet->getHighestRow());
        $currentRow++;
        $sheet->getCell('A' . $currentRow)->setValue('Итого')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('B' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('C' . $currentRow)->setValue(Format::number($totalAmount) . ' ' . $row->currency)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('D' . $currentRow)->setValue(Format::number($totalPayedAmount) . ' ' . $row->currency)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('E' . $currentRow)->setValue(Format::number($totalRemainingAmount) . ' ' . $row->currency)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($remainingCellFont);
        $sheet->getCell('F' . $currentRow)->setValue($totalCountBookings)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getRowDimension($currentRow)->setRowHeight(15);
    }

    private function appendBookingsRows(array $bookings, array $guests): void
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
            'Отели',
            'Дата заезда',
            'Дата выезда',
            'Кол-во номеров',
            'Кол-во ночей',
            'Кол-во номер*ночей',
            'ФИО гостей',
            'Кол-во гостей',
        ];

        $style = $this->getCellStyle('5B9BD5', true);
        $boldFont = [...$this->defaultFont, 'bold' => true];

        foreach ($headerRow as $index => $value) {
            $sheet->getCell([++$index, $currentRow])
                ->setValue($value)
                ->getStyle()->applyFromArray($style)
                ->getFont()->applyFromArray($boldFont);
        }

        $totalRoomsCount = 0;
        $totalNights = 0;
        $totalRoomNights = 0;
        $totalGuestCount = 0;
        foreach ($bookings as $index => $booking) {
            $bookingStartDate = strtotime($booking->date_start);
            $bookingEndDate = strtotime($booking->date_end);
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

            $bgColor = ($index === 0 || $index % 2 === 0) ? 'FFFFFF' : 'DEEAF6';
            $cellStyle = $this->getCellStyle($bgColor);
            $this->insertNewRowBefore($sheet->getHighestRow());
            $currentRow++;

            $bookingId = (int)$booking->id;
            $roomsCount = (int)$booking->rooms_count;
            $nightsCount = (int)$booking->nights_count;
            $roomNightsCount = $roomsCount * $nightsCount;
            $bookingGuests = $guests[$bookingId];
            $guestsCount = count($bookingGuests);

            $totalRoomsCount += $roomsCount;
            $totalNights += $nightsCount;
            $totalRoomNights += $roomNightsCount;
            $totalGuestCount += $guestsCount;
            $data = [
                'A' . $currentRow => $bookingId,
                'B' . $currentRow => $booking->hotel_name,
                'C' . $currentRow => date('d/m/Y', $bookingStartDate),
                'D' . $currentRow => date('d/m/Y', $bookingEndDate),
                'E' . $currentRow => $roomsCount,
                'F' . $currentRow => $nightsCount,
                'G' . $currentRow => $roomNightsCount,
                'H' . $currentRow => implode(', ', $bookingGuests),
                'I' . $currentRow => $guestsCount,
            ];

            foreach ($data as $coordinates => $value) {
                $sheet->getCell($coordinates)
                    ->setValue($value)
                    ->getStyle()
                    ->applyFromArray($cellStyle)
                    ->getFont()
                    ->applyFromArray($this->defaultFont);
            }
        }

        $cellStyle = $this->getCellStyle('FFFFFF', true);
        $this->insertNewRowBefore($sheet->getHighestRow());
        $currentRow++;
        $sheet->getCell('A' . $currentRow)->setValue('Итого')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('B' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('C' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('D' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('E' . $currentRow)->setValue($totalRoomsCount)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('F' . $currentRow)->setValue($totalNights)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('G' . $currentRow)->setValue($totalRoomNights)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('H' . $currentRow)->setValue('')->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getCell('I' . $currentRow)->setValue($totalGuestCount)->getStyle()->applyFromArray($cellStyle)->getFont()->applyFromArray($boldFont);
        $sheet->getRowDimension($currentRow)->setRowHeight(15);
    }
}
