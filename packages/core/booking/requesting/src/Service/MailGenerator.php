<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Service;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Service\MailTemplateCompilerInterface;
use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Pkg\Booking\Requesting\Service\TemplateRenderer\TemplateDataFactory;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

class MailGenerator
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly MailTemplateCompilerInterface $mailTemplateCompiler,
    ) {}

    public function getSubject(BookingRequest $request): string
    {
        /** @var string $subject */
        $subject = match ($request->type()) {
            RequestTypeEnum::BOOKING => __('Запрос на бронирование'),
            RequestTypeEnum::CHANGE => __('Запрос на изменение'),
            RequestTypeEnum::CANCEL => __('Запрос на отмену'),
        };

        return "#{$request->bookingId()->value()} - {$subject}";
    }

    public function generate(BookingRequest $request): string
    {
        $booking = $this->bookingRepository->findOrFail($request->bookingId());
        $templateName = $this->getTemplateName($booking->serviceType(), $request->type());

        $commonData = $this->templateDataFactory->buildCommon($booking);
        $templateData = $this->templateDataFactory->build($booking, $request->type());
        $data = [
            ...$commonData->toArray(),
            ...$templateData->toArray(),
        ];

        return $this->mailTemplateCompiler->compile($templateName, $data);
    }

    private function getTemplateName(ServiceTypeEnum $serviceType, RequestTypeEnum $requestType): string
    {
        $name = '';
        if ($serviceType === ServiceTypeEnum::HOTEL_BOOKING) {
            $name .= 'hotel';
        } elseif ($serviceType->isAirportService()) {
            $name .= 'airport';
        } elseif ($serviceType->isTransferService()) {
            $name .= 'transfer';
        } else {
            $name .= 'other';
        }

        $name .= '.';
        $name .= match ($requestType) {
            RequestTypeEnum::BOOKING => 'booking',
            RequestTypeEnum::CHANGE => 'change',
            RequestTypeEnum::CANCEL => 'cancel'
        };
        $name .= '_request';

        return "BookingRequesting::mail.$name";
    }
}
