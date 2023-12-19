<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Service;

use Illuminate\Contracts\View\View;
use Module\Booking\Shared\Domain\Shared\Service\MailTemplateCompilerInterface;

class BladeTemplateCompiler implements MailTemplateCompilerInterface
{
    public function compile(string $template, array $attributes): string
    {
        return $this->getTemplateView($template, $attributes)->render();
    }

    private function getTemplateView(string $template, array $attributes): View
    {
        return view($template, $attributes);
    }
}
