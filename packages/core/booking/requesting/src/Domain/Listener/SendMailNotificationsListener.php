<?php

namespace Pkg\Booking\Requesting\Domain\Listener;

use Illuminate\Support\Facades\Validator;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Pkg\Booking\Requesting\Domain\Adapter\HotelAdapterInterface;
use Pkg\Booking\Requesting\Domain\Adapter\SupplierAdapterInterface;
use Pkg\Booking\Requesting\Domain\Event\BookingRequestEventInterface;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;
use Sdk\Shared\Dto\Mail\AttachmentDto;
use Shared\Contracts\Adapter\MailAdapterInterface;

class SendMailNotificationsListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly MailAdapterInterface $mailAdapter,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingRequestEventInterface);

//        $booking = $this->bookingUnitOfWork->findOrFail($event->bookingId());
        $details = $this->bookingUnitOfWork->getDetails($event->bookingId());

        if ($details instanceof HotelBooking) {
            $email = $this->hotelAdapter->getEmail($details->hotelInfo()->id());
        } else {
            $email = $this->supplierAdapter->getEmail($details->serviceInfo()->supplierId());
        }

        if (!$this->isValidEmail($email)) {
            return;
        }

        $request = $this->requestRepository->find($event->requestId());
        if ($request === null) {
            return;
        }

        //@todo получить пример письма запроса от Анвара
        $this->mailAdapter->sendTo(
            $email,
            $this->getSubject($request->type()),
            'Запрос',
            [new AttachmentDto($request->file()->guid())]
        );
    }

    private function getSubject(RequestTypeEnum $type): string
    {
        return match ($type) {
            RequestTypeEnum::BOOKING => __('Запрос на бронирование'),
            RequestTypeEnum::CHANGE => __('Запрос на изменение'),
            RequestTypeEnum::CANCEL => __('Запрос на отмену'),
        };
    }

    private function isValidEmail(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'required', 'email']
        );

        return $validator->passes();
    }
}
