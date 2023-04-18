<?php

namespace Module\Booking\Common\Domain\Service\DocumentGenerator;

class DocumentBuilder
{
    private $mpdf;

    private string $template;

    private array $attributes = [];

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
        $this->mpdf->WriteHTML($this->prepareTemplate());

        return $this->mpdf->OutputBinaryData();
    }

    public function template(string $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function attributes(array $attributes): static
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function attribute(string $name, string $value): static
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function image(string $name, string $content): static
    {
        $this->mpdf->imageVars[$name] = $content;
        $this->mpdf->Image('var:' . $name, 0, 0);
        return $this;
    }

    private function prepareTemplate(): string
    {
        $html = $this->template;

        foreach ($this->attributes as $key => $requisite) {
            $html = str_replace('{' . $key . '}', $requisite, $html);
        }

        return $html;
    }
}
