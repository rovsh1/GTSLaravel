<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service;

use Illuminate\Support\Arr;
use Module\Booking\Invoicing\Domain\Service\Dto\ClientDto;
use Module\Booking\Invoicing\Domain\Service\Dto\CompanyRequisitesDto;
use Module\Booking\Invoicing\Domain\Service\Dto\InvoiceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ManagerDto;
use Module\Booking\Invoicing\Domain\Service\Dto\OrderDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\ListItem;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Enum\CurrencyEnum;

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

        /** @var InvoiceDto $invoice */
        $invoice = $templateData['invoice'];
        /** @var CompanyRequisitesDto $companyRequisites */
        $companyRequisites = $templateData['company'];
        /** @var ManagerDto $manager */
        $manager = $templateData['manager'];
        /** @var ClientDto $client */
        $client = $templateData['client'];
        /** @var OrderDto $order */
        $order = $templateData['order'];

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
            __('ИНВОЙС №:number', ['number' => $invoice->number]),
            ['name' => 'Arial', 'size' => 24, 'color' => '1B2232', 'bold' => true],
            $alignRightStyle
        );

        $section->addText(
            __('Дата создания: :date', ['date' => $invoice->createdAt]),
            ['name' => 'Arial', 'size' => 8, 'color' => '000000'],
            $alignRightStyle
        );

        $section->addTextBreak();

        $section->addText(
            $companyRequisites->name,
            ['name' => 'Arial', 'size' => 8, 'color' => '000000', 'bold' => true],
            $alignRightStyle
        );

        $section->addText(
            __('Тел: :phone', ['phone' => $companyRequisites->phone]),
            ['name' => 'Arial', 'size' => 8, 'color' => '000000'],
            $alignRightStyle
        );

        $section->addLink(
            "mailto:{$companyRequisites->email}",
            $companyRequisites->email,
            ['name' => 'Arial', 'size' => 8, 'color' => '0000FF', 'underline' => 'single'],
            $alignRightStyle
        );

        $section->addText(
            $companyRequisites->legalAddress,
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

        $cell = $table->addRow()->addCell($sectionWidth, [
            'borderTopSize' => 12,
            'borderTopColor' => '999999',
            'borderBottomSize' => 12,
            'borderBottomColor' => '999999'
        ]);
        $textRun = $cell->addTextRun();
        $textRun->addText(
            __('Клиент') . ': ' . $client->name . PHP_EOL,
            ['name' => 'Arial', 'size' => 10, 'color' => '000000'],
        );

        $textRun->addText(
            __('Договор') . ': ' . $client->contractNumber . PHP_EOL,
            ['name' => 'Arial', 'size' => 10, 'color' => '000000'],
        );

        $textRun->addText(
            __('Адрес') . ': ' . $client->address . PHP_EOL,
            ['name' => 'Arial', 'size' => 10, 'color' => '000000'],
        );

        $textRun->addText(
            'Email: ',
            ['name' => 'Arial', 'size' => 10, 'color' => '000000'],
        );
        if (!empty($client->email)) {
            $textRun->addLink(
                "mailto:{$client->email}",
                $client->email,
                ['name' => 'Arial', 'size' => 10, 'color' => '0000FF', 'underline' => 'single'],
            );
        }

        $table = $section->addTable([
            'cellMargin' => 50,
            'width' => $sectionWidth,
            'unit' => 'pct',
            'alignment' => JcTable::CENTER
        ]);

        foreach ($templateData['services'] as $service) {
            $this->appendService($table, $sectionWidth, $service);
        }

        $row = $table->addRow();
        $row->addCell($sectionWidth * 0.5)->addText(
            __('ИТОГО К ОПЛАТЕ'),
            ['name' => 'Arial', 'size' => 14, 'color' => '000000', 'bold' => true]
        );
        $amount = $invoice->totalPenalty > 0 ? $invoice->totalPenalty : $invoice->totalAmount;
        $row->addCell($sectionWidth * 0.5, ['gridSpan' => 3])->addText(
            \Format::number($amount) . ' ' . $order->currency,
            ['name' => 'Arial', 'size' => 14, 'color' => '000000', 'bold' => true],
            ['alignment' => Jc::END]
        );

        $section->addTextBreak();

        $textRun = $section->addTextRun();
        $textRun->addText(
            __('Важное примечание: '),
            ['name' => 'Arial', 'size' => 10, 'color' => '000000', 'underline' => 'single', 'italic' => true]
        );
        $textRun->addText(
            __(
                "Оплата должна быть произведена в полной мере, без учета комиссии межбанковских переводов, налогов и сборов.\nВ случае необходимости уплаты таковых, все расчеты должны быть произведены заказчиком сверх сумм, указанных в настоящем инвойсе."
            ),
            ['name' => 'Arial', 'size' => 10, 'color' => '000000', 'italic' => true],
        );

        $section->addTextBreak();
        $this->appendCompanyRequisites($section, $companyRequisites);

        $section->addTextBreak(2);
        $section->addText(__('Спасибо за сотрудничество.'));

        $section->addTextBreak(2);
        $section->addText($companyRequisites->name);
        $section->addText(__('Директор: :signer', ['signer' => $companyRequisites->signer]));

        $section->addTextBreak(2);
        $section->addText(__('Менеджер') . ': ' . $manager->fullName);
        $section->addText('E-mail: ' . $manager->email);
        $section->addText(__('Мобильный номер') . ': ' . $manager->phone);

        $section->addImage(
            storage_path('app/public/company-stamp-with-sign.png'),
            [
                'positioning' => 'absolute',
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'right',
                'posHorizontalRel' => 'margin',
                'marginTop' => -600,//@todo расположение печати
            ]
        );

        $writter = IOFactory::createWriter($phpWord);
        $writter->save($tempFilePath);

        $content = file_get_contents($tempFilePath);

        return $this->fileStorageAdapter->create($filename, $content);
    }

    private function appendCompanyRequisites(Section $section, CompanyRequisitesDto $companyRequisites): void
    {
        $sectionStyle = $section->getStyle();
        $sectionWidth = $sectionStyle->getPageSizeW() - $sectionStyle->getMarginLeft() - $sectionStyle->getMarginRight();
        $tableWidth = $sectionWidth * 0.7;
        $table = $section->addTable([
            'cellMargin' => 50,
            'width' => $tableWidth,
            'unit' => 'pct',
            'alignment' => JcTable::START
        ]);

        $paragraphStyle = [
            'spaceAfter' => 0, // Уменьшаем пространство после параграфа
            'lineHeight' => 1.0, // Устанавливаем межстрочный интервал (1.0 для одинарного интервала)
        ];
        foreach ($this->getCompanyRequisitesRows($companyRequisites) as $requisite) {
            ['label' => $label, 'value' => $value] = $requisite;
            $row = $table->addRow();
            $row->addCell($tableWidth * 0.4)->addText($label, null, $paragraphStyle);
            $row->addCell($tableWidth * 0.6)->addText($value, null, $paragraphStyle);
        }
    }

    private function getCompanyRequisitesRows(CompanyRequisitesDto $companyRequisites): array
    {
        return [
            ['label' => __('Бенефициар'), 'value' => $companyRequisites->name],
            ['label' => __('Адрес'), 'value' => $companyRequisites->legalAddress],
            ['label' => __('Тел'), 'value' => $companyRequisites->phone],
            ['label' => __('ИНН'), 'value' => '305768069'],
            ['label' => __('ОКЭД'), 'value' => '79900'],
            ['label' => __('Банк'), 'value' => 'ЧАКБ «ORIENT FINANCE BANK» Мирабадский филиал'],
            ['label' => __('Адрес'), 'value' => '7А, ул. Якуб Колас, г. Ташкент, 100023, Узбекистан'],
            ['label' => __('МФО'), 'value' => '01071'],
            ['label' => __('Код ЦБУ'), 'value' => '11795'],

            ['label' => '', 'value' => ''],
            [
                'label' => __('Р/с в :currency', ['currency' => CurrencyEnum::UZS->name]),
                'value' => '20208000300934341001'
            ],
            [
                'label' => __('Р/с в :currency', ['currency' => CurrencyEnum::USD->name]),
                'value' => '20208840800934341002'
            ],
            [
                'label' => __('Р/с в :currency', ['currency' => CurrencyEnum::EUR->name]),
                'value' => '20208978100934341002'
            ],
            [
                'label' => __('Р/с в :currency', ['currency' => CurrencyEnum::RUB->name]),
                'value' => '20208643900934341002'
            ],
            ['label' => 'SWIFT', 'value' => 'ORFBUZ22'],

            ['label' => '', 'value' => ''],
            ['label' => __('Банк корреспондент'), 'value' => 'АКБ "Азия-Инвест Банк"'],
            ['label' => __('БИК'), 'value' => '044525234'],
            ['label' => __('Телекс'), 'value' => '914624 ASINV RU'],
            ['label' => 'SWIFT', 'value' => 'ASIJRUMM'],
            [
                'label' => __('Кор.сч. в :currency', ['currency' => CurrencyEnum::USD->name]),
                'value' => '30111840800000002535',
            ],
            [
                'label' => __('Кор.сч. в :currency', ['currency' => CurrencyEnum::EUR->name]),
                'value' => '30111978400000002535',
            ],
            [
                'label' => __('Кор.сч. в :currency', ['currency' => CurrencyEnum::RUB->name]),
                'value' => '30111810500000002535',
            ]
        ];
    }

    private function appendService(Table $table, float $sectionWidth, ServiceInfoDto $service): void
    {
        $boldTextStyle = ['name' => 'Arial', 'size' => 10, 'color' => '000000', 'bold' => true];
        $topCellStyle = ['borderTopSize' => 12, 'borderTopColor' => '999999'];
        $row = $table->addRow();
        $countCellWidth = $sectionWidth * 0.5 / 3;
        $row->addCell($sectionWidth * 0.5, $topCellStyle)->addText(__('Информация об услуге'), $boldTextStyle);
        $row->addCell($countCellWidth, $topCellStyle)->addText(
            __('Кол-во'),
            $boldTextStyle,
            ['alignment' => Jc::CENTER]
        );
        $row->addCell($countCellWidth, $topCellStyle)->addText(
            __('Цена'), $boldTextStyle,
            ['alignment' => Jc::CENTER]
        );
        $row->addCell($countCellWidth, $topCellStyle)->addText(
            __('Итого'), $boldTextStyle,
            ['alignment' => Jc::CENTER]
        );

        if (count($service->guests) > 0) {
            $cell = $table->addRow()->addCell($sectionWidth, ['gridSpan' => 4]);
            $cell->addText(__('Гости (:count)', ['count' => count($service->guests)]));
            foreach ($service->guests as $guest) {
                $cell->addListItem("{$guest->fullName} ($guest->countryName)", 0, null, ListItem::TYPE_NUMBER);
            }
        }
        $row = $table->addRow();
        $cell = $row->addCell($sectionWidth * 0.5);
        $textRun = $cell->addTextRun();
        $textRun->addText("{$service->title}\n", $boldTextStyle);
        foreach ($service->detailOptions as $detailOption) {
            $textRun->addText("{$detailOption->label}: {$detailOption->getHumanValue()}\n");
        }
        $row->addCell($countCellWidth, ['valign' => 'bottom'])->addText(
            (string)$service->price->quantity,
            null,
            ['alignment' => Jc::CENTER]
        );
        $row->addCell($countCellWidth, ['valign' => 'bottom'])->addText(
            \Format::number($service->price->amount) . ' ' . $service->price->currency,
            null,
            ['alignment' => Jc::CENTER]
        );
        $row->addCell($countCellWidth, ['valign' => 'bottom'])->addText(
            \Format::number($service->price->total) . ' ' . $service->price->currency,
            null,
            ['alignment' => Jc::CENTER]
        );

        $table->addRow()->addCell($sectionWidth, ['gridSpan' => 4, 'borderTopSize' => 12, 'borderTopColor' => '999999']
        );
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
