<?php

namespace Module\Support\MailManager\Infrastructure\Service\DataBuilder\Support;

use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\BookingRequestDataDto;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\DataDtoInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\Booking\BookingRequest;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;

abstract class AbstractBookingDataBuilder
{
    final public function build(
        TemplateInterface $template,
        DataDtoInterface $dataDto,
        RecipientInterface $recipient
    ): DataInterface {
        if ($template instanceof BookingRequest) {
            assert($dataDto instanceof BookingRequestDataDto);

            return $this->buildBookingRequestData($dataDto);
        } else {
            throw new \Exception('DataBuilder for ' . $template::class . ' not implemented');
        }
    }

    abstract protected function buildBookingRequestData(BookingRequestDataDto $dataDto): DataInterface;
}