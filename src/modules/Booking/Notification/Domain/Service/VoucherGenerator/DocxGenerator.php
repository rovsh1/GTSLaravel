<?php

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator;

use Illuminate\Support\Arr;
use Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\CompanyRequisitesDto;
use Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\ManagerDto;
use Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\ServiceInfoDto;
use Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\VoucherDto;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\ListItem;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;

class DocxGenerator
{
    private mixed $tempFile;

    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly TemplateDataFactory $templateDataFactory,
    ) {}

    public function __destruct()
    {
        if (isset($this->tempFile) && is_resource($this->tempFile)) {
            fclose($this->tempFile);
        }
    }

    public function generate(string $filename, OrderId $orderId): FileDto
    {
        $templateData = $this->templateDataFactory->build($orderId);

        /** @var VoucherDto $voucher */
        $voucher = $templateData['voucher'];
        /** @var CompanyRequisitesDto $company */
        $company = $templateData['company'];
        /** @var ManagerDto $manager */
        $manager = $templateData['manager'];

        $tempFilePath = $this->getTempFilePath();

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        $section->addImage(
            storage_path('app/public/logo_docx.jpg'),
            [
                'width' => Converter::cmToPixel(5.03),
                'height' => Converter::cmToPixel(2.81),
                'positioning' => 'absolute',
                'posHorizontal' => 'left',
                'posVertical' => 'top',
                'wrappingStyle' => 'behind',
                'marginTop' => Converter::cmToPixel(1.65),
                'marginLeft' => Converter::cmToPixel(1.66),
            ]
        );

        $alignRightStyle = ['align' => 'end'];
        $section->addText(
            __('ВАУЧЕР №:number', ['number' => $voucher->number]),
            ['name' => 'Arial', 'size' => 24, 'color' => '1B2232', 'bold' => true],
            $alignRightStyle
        );

        $section->addText(
            __('Дата создания: :date', ['date' => $voucher->createdAt]),
            ['name' => 'Arial', 'size' => 8, 'color' => '000000'],
            $alignRightStyle
        );

        $section->addTextBreak();

        $section->addText(
            $company->name,
            ['name' => 'Arial', 'size' => 8, 'color' => '000000', 'bold' => true],
            $alignRightStyle
        );

        $section->addText(
            __('Тел: :phone', ['phone' => $company->phone]),
            ['name' => 'Arial', 'size' => 8, 'color' => '000000'],
            $alignRightStyle
        );

        $section->addLink(
            "mailto:{$company->email}",
            $company->email,
            ['name' => 'Arial', 'size' => 8, 'color' => '0000FF', 'underline' => 'single'],
            $alignRightStyle
        );

        $section->addText(
            $company->legalAddress,
            ['name' => 'Arial', 'size' => 8, 'color' => '000000'],
            $alignRightStyle
        );

        $section->addTextBreak(2);

        $sectionStyle = $section->getStyle();
        $sectionWidth = $sectionStyle->getPageSizeW() - $sectionStyle->getMarginLeft() - $sectionStyle->getMarginRight();
        $table = $section->addTable([
            'cellMargin' => 50,
            'width' => $sectionWidth,
            'unit' => 'pct',
            'alignment' => JcTable::CENTER
        ]);

        foreach ($templateData['services'] as $service) {
            $this->appendService($table, $sectionWidth, $service);
        }

        $section->addTextBreak();
        $section->addText(__('Менеджер') . ': ' . $manager->fullName);
        $section->addText('E-mail: ' . $manager->email);
        $section->addText(__('Мобильный номер') . ': ' . $manager->phone);

        $writter = IOFactory::createWriter($phpWord);
        $writter->save($tempFilePath);

        $content = file_get_contents($tempFilePath);

        return $this->fileStorageAdapter->create($filename, $content);
    }

    private function appendService(Table $table, float $sectionWidth, ServiceInfoDto $service): void
    {
        $boldTextStyle = ['name' => 'Arial', 'size' => 10, 'color' => '000000', 'bold' => true];
        $topCellStyle = ['gridSpan' => 2, 'borderTopSize' => 12, 'borderTopColor' => '999999'];
        $table->addRow()->addCell($sectionWidth, $topCellStyle)->addText(__('Информация об услуге'), $boldTextStyle);

        if (count($service->guests) > 0) {
            $cell = $table->addRow()->addCell($sectionWidth, ['gridSpan' => 2]);
            $cell->addText(__('Гости (:count)', ['count' => count($service->guests)]));
            foreach ($service->guests as $guest) {
                $cell->addListItem("{$guest->fullName} ($guest->countryName)", 0, null, ListItem::TYPE_NUMBER);
            }
        }

        $row = $table->addRow();
        $cell = $row->addCell($sectionWidth * 0.6);

        $textRun = $cell->addTextRun();
        $textRun->addText("{$service->title}\n", $boldTextStyle);
        foreach ($service->detailOptions as $detailOption) {
            $textRun->addText("{$detailOption->label}: {$detailOption->getHumanValue()}\n");
        }

        $textRun = $row->addCell($sectionWidth * 0.4)->addTextRun();
        $textRun->addText(__('Статус') . ": {$service->status}\n");
        $textRun->addText(__('Номер подтверждения') . ": \n");


        $row = $table->addRow();
        $textRun = $row->addCell($sectionWidth, ['gridSpan' => 2, 'borderBottomSize' => 12, 'borderBottomColor' => '999999'])->addTextRun();
        $textRun->addText(__('Условия отмены:') . PHP_EOL);
        $textRun->addText(
            __('Отмена без штрафа до :date', [
                'date' => $service->cancelConditions?->cancelNoFeeDate
                    ? \Format::date($service->cancelConditions?->cancelNoFeeDate)
                    : '-'
            ]) . PHP_EOL
        );

        if ($service->cancelConditions) {
            $textRun->addText(
                __('Неявка: :percent% :type', [
                    'percent' => $service->cancelConditions->noCheckInMarkup,
                    'type' => $service->cancelConditions->noCheckInMarkupType
                ]) . PHP_EOL
            );

            foreach ($service->cancelConditions->dailyMarkups ?? [] as $dailyMarkup) {
                $markupTypeText = $dailyMarkup->valueType === \Sdk\Shared\Enum\Pricing\ValueTypeEnum::PERCENT
                    ? "{$dailyMarkup->value}% {$dailyMarkup->markupType}"
                    : "{$dailyMarkup->value} {$service->price->currency}";

                $markupText = __('За :days :dimension', [
                    'days' => $dailyMarkup->daysCount,
                    'dimension' => trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount)
                ]);

                $markupText .= ": {$markupTypeText}";

                $textRun->addText($markupText . PHP_EOL);
            }
        }

        $table->addRow()->addCell($sectionWidth, ['gridSpan' => 2]);
    }

    private function getTempFilePath(): string
    {
        if (!isset($this->tempFile)) {
            $this->tempFile = tmpfile();
        }
        $tempFileMetadata = stream_get_meta_data($this->tempFile);
        return Arr::get($tempFileMetadata, 'uri');
    }
}
