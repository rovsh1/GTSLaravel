<?php

namespace Gsdk\Form\Renderer;

class Compiler
{
    public static function compile(string $template, array $data): string
    {
        extract($data);

        ob_start();
        require __DIR__ . '/../views/' . $template . '.blade.php';
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
