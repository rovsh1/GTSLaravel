<?php

namespace Module\Services\MailManager\Infrastructure\Repository;

use Module\Services\MailManager\Domain\Entity\MailTemplateInterface;
use Module\Services\MailManager\Domain\MailTemplate\TemplatesEnum;
use Module\Services\MailManager\Domain\Repository\MailTemplateRepositoryInterface;
use Module\Services\MailManager\Infrastructure\Model\MailTemplate;

class MailTemplateRepository implements MailTemplateRepositoryInterface
{
    public function find(TemplatesEnum $template): ?MailTemplateInterface
    {
        $model = MailTemplate::where('key', $template->key())
            ->limit(1)
            ->first();
        if (!$model) {
            return null;
        }

        return $model;
    }

    public function getMailData(MailTemplateInterface $mail): ?array
    {
        $model = MailTemplate::find($mail->key());
        if (!$model) {
            return null;
        }

        return $model->toArray();
    }
}