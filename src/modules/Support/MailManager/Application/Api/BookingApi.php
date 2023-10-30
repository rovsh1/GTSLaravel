<?php

namespace Module\Support\MailManager\Application\Api;

use Module\Support\MailManager\Application\RequestDto\Booking\SendBookingRequestRequestDto;
use Module\Support\MailManager\Application\Service\MailTemplateSender;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\BookingRequestDataDto;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\Booking\BookingRequest;
use Sdk\Module\Contracts\Api\ApiInterface;

final class BookingApi implements ApiInterface
{
    public function __construct(private readonly MailTemplateSender $templateSender)
    {
    }

    public function sendBookingRequest(SendBookingRequestRequestDto $requestDto): void
    {
        $this->templateSender->send(
            new BookingRequest,
            new BookingRequestDataDto($requestDto->bookingId, $requestDto->requestId),
            $requestDto->context
        );
    }

    public function sendChangeRequest(SendBookingRequestRequestDto $requestDto): void
    {
        $this->templateSender->send(
            new BookingRequest,
            new BookingRequestDataDto($requestDto->bookingId, $requestDto->requestId),
            $requestDto->context
        );
    }

    public function test(): void
    {
        $this->sendBookingRequest(
            new SendBookingRequestRequestDto(
                100, 1
            )
        );
        dd(1);
    }
}