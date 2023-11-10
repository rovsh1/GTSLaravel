<?php

namespace Module\Client\Invoicing\Infrastructure\Service;

use Illuminate\Contracts\View\View;
use Module\Shared\Service\TemplateCompilerInterface;
use Mpdf\Mpdf;

class InvoiceTemplateCompiler implements TemplateCompilerInterface
{
    private string $imagesPath;

    public function __construct()
    {
        $this->imagesPath = storage_path("app/public");
    }

    public function compile(string $template, array $attributes): string
    {
        $mpdf = $this->makeMpdf();

        $mpdf->SetDisplayMode('fullpage');
        $this->prepareImages($mpdf);

        $mpdf->WriteHTML($this->getTemplateView($template, $attributes)->render());

        return $mpdf->OutputBinaryData();
    }

    private function makeMpdf(): Mpdf
    {
        $tempPath = sys_get_temp_dir();

        return new Mpdf([
            'tempDir' => $tempPath,
            'mode' => 'utf-8',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
    }

    protected function prepareImages(Mpdf $mpdf): void
    {
        static $images = [
            'logo' => 'company-logo-small.png',
            'stamp' => 'company-stamp-with-sign.png',
            'stamp_only' => 'company-stamp-without-sign.png',
        ];
        foreach ($images as $key => $filename) {
            $mpdf->imageVars[$key] = file_get_contents("$this->imagesPath/$filename");
            $mpdf->Image('var:' . $key, 0, 0);
        }
    }

    private function getTemplateView(string $template, array $attributes): View
    {
        return view($template, $attributes);
    }
}
