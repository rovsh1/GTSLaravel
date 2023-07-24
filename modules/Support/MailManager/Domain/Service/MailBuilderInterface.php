<?php

namespace Module\Support\MailManager\Domain\Service;

use Module\Support\MailManager\Domain\Entity\Mail;
use Module\Support\MailManager\Domain\Entity\QueueMessage;

interface MailBuilderInterface
{
    public function build(QueueMessage $queueMessage): Mail;
}