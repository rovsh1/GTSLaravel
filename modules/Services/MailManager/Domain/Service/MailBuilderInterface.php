<?php

namespace Module\Services\MailManager\Domain\Service;

use Module\Services\MailManager\Domain\Entity\Mail;
use Module\Services\MailManager\Domain\Entity\QueueMessage;

interface MailBuilderInterface
{
    public function build(QueueMessage $queueMessage): Mail;
}