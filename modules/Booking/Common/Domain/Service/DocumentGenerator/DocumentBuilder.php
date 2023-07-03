<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

class DocumentBuilder
{
    private $mpdf;

    private string $template;

    public function __construct()
    {
        $tempPath = sys_get_temp_dir();
        $this->mpdf = new \Mpdf\Mpdf([
            'tempDir' => $tempPath,
            'mode' => 'utf-8',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
    }

    public function generate(): string
    {
        $this->mpdf->SetDisplayMode('fullpage');
        $this->mpdf->WriteHTML($this->template);

        return $this->mpdf->OutputBinaryData();
    }

    public function template(string $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function image(string $name, string $content): static
    {
        $this->mpdf->imageVars[$name] = $content;
        $this->mpdf->Image('var:' . $name, 0, 0);
        return $this;
    }
}
