<?php

namespace Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\Booking;

use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\BookingTemplateInterface;

class BookingRequest implements BookingTemplateInterface
{
    public function key(): string
    {
        return 'booking-request';
    }

    public function subject(): string
    {
        return 'Запрос на бронирование';
    }
}