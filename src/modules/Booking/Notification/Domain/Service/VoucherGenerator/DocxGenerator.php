<?php

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator;

use Module\Booking\Shared\Domain\Shared\Service\TemplateCompilerInterface;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Title;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;

class DocxGenerator
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly TemplateCompilerInterface $templateCompiler,
    ) {}

    public function generate(string $filename, OrderId $orderId): FileDto
    {
        $templateData = $this->templateDataFactory->build($orderId);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(
            resource_path('voucher-templates/voucher_template.docx')
        );

        $table = new Table();
        // Добавление строки
        $table->addRow();

// Добавление ячеек в строку и заполнение их данными
        $table->addCell(1750)->addText("Ячейка 1");
        $table->addCell(1750)->addText("Ячейка 2");

// Добавление ещё одной строки
        $table->addRow();

// Добавление ячеек во вторую строку
        $table->addCell(1750)->addText("Данные 1");
        $table->addCell(1750)->addText("Данные 2");

        $sect = new Title('Новый ткст');

        $templateProcessor->setComplexBlock('{table}', $sect);

        $templateProcessor->setValue('{number}', $templateData['voucher']->number);
        $templateProcessor->setValue('{createdAt}', $templateData['voucher']->createdAt);

        $tempFile = $templateProcessor->save();

        $content = file_get_contents($tempFile);

        return $this->fileStorageAdapter->create(str_replace('.pdf', '.docx', $filename), $content);
    }
}
