<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

abstract class AbstractGenerator
{
    protected readonly string $templatesPath;

    public function __construct()
    {
        $this->templatesPath = __DIR__ . DIRECTORY_SEPARATOR . 'templates';
    }

    abstract public function getDocumentPath(): string;

    protected function generatePdf(array $requisites, string $fileName): string
    {
        $tempPath = sys_get_temp_dir();
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => $tempPath,
            'mode' => 'utf-8',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
        $mpdf->imageVars['logo'] = $this->getTemplateFileContent('logo.jpg');
        $mpdf->Image("var:logo", 0, 0);
        $mpdf->imageVars['stamp'] = $this->getTemplateFileContent('stamp.jpg');
        $mpdf->Image("var:stamp", 0, 0);
        $mpdf->imageVars['stamp_only'] = $this->getTemplateFileContent('stamp_only.jpg');
        $mpdf->Image("var:stamp_only", 0, 0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($this->getDocumentHtml($requisites));

        //$destination = TEMP_PATH . '/' . $fileName;
        //$mpdf->Output($destination);

        return '1';//TODO return document content
    }

    private function getDocumentHtml(array $requisites): string
    {
        $documentPath = $this->getDocumentPath();
        if (!file_exists($documentPath))
            throw new \LogicException('Document [' . $this::class . '] template undefined');

        $html = $this->getTemplateFileContent($documentPath);

        foreach ($requisites as $key => $requisite) {
            $html = str_replace('{' . $key . '}', $requisite, $html);
        }

        return $html;
    }

    private function getTemplateFileContent($name): string
    {
        return file_get_contents($this->templatesPath . DIRECTORY_SEPARATOR . $name);
    }
}
