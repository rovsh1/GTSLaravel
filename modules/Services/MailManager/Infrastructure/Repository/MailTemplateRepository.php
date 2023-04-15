<?php

namespace Module\Services\MailManager\Infrastructure\Repository;

use Module\Services\MailManager\Domain\Entity\MailTemplateInterface;
use Module\Services\MailManager\Domain\Repository\MailTemplateRepositoryInterface;
use Module\Services\MailManager\Infrastructure\Model\Mail;

class MailTemplateRepository implements MailTemplateRepositoryInterface
{
    public function getMailData(MailTemplateInterface $mail): ?array
    {
        $model = Mail::find($mail->key());
        if (!$model) {
            return null;
        }

        return $model->toArray();
    }
}