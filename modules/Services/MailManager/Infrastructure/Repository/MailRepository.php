<?php

namespace Module\Services\MailManager\Infrastructure\Repository;

use Module\Services\MailManager\Domain\Mail\MailInterface;
use Module\Services\MailManager\Domain\Repository\MailRepositoryInterface;
use Module\Services\MailManager\Infrastructure\Model\Mail;

class MailRepository implements MailRepositoryInterface
{
    public function getMailData(MailInterface $mail): ?array
    {
        $model = Mail::find($mail->key());
        if (!$model) {
            return null;
        }

        return $model->toArray();
    }
}