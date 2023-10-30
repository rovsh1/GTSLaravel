<?php

namespace Module\Support\MailManager\Infrastructure\Service\DataBuilder;

use Illuminate\Support\Facades\DB;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\BookingDataDtoInterface;
use Module\Support\MailManager\Domain\Service\RecipientsFinder\Recipient\RecipientInterface;
use Module\Support\MailManager\Domain\Service\TemplateRenderer\Template\TemplateInterface;
use Module\Support\MailManager\Infrastructure\Service\DataBuilder\Booking\HotelBookingDataBuilder;
use Sdk\Module\Contracts\Support\ContainerInterface;

class BookingDataBuilder
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function build(
        TemplateInterface $template,
        BookingDataDtoInterface $dataDto,
        RecipientInterface $recipient
    ): DataInterface {
        $bookingType = DB::table('bookings')->where('id', $dataDto->bookingId)->value('type');

        switch ($bookingType) {
            case BookingTypeEnum::HOTEL->value:
                return $this->container->make(HotelBookingDataBuilder::class)->build($template, $dataDto, $recipient);
            default:
                throw new \LogicException('Not implemented');
        }
    }
}
