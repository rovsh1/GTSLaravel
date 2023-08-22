<?php

namespace Module\Support\MailManager\Infrastructure\Service;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\TemplateRendererInterface;

class MailTemplateRenderer implements TemplateRendererInterface
{
    public function render(TemplateInterface $mailTemplate, DataInterface $data): string
    {
        $factory = app(ViewFactory::class);
        $view = $this->getViewPath($mailTemplate->key());

        return (string)$factory->file($view, $data->toArray());
    }

    private function getViewPath(string $view): string
    {
        $serviceKey = 'hotel-booking';

        return root_path("resources/mail/$serviceKey/$view.blade.php");
    }
}