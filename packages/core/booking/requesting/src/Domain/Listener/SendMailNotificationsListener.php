<?php

namespace Pkg\Booking\Requesting\Domain\Listener;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Pkg\Booking\Requesting\Domain\Adapter\AdministratorAdapterInterface;
use Pkg\Booking\Requesting\Domain\Adapter\HotelAdapterInterface;
use Pkg\Booking\Requesting\Domain\Adapter\SupplierAdapterInterface;
use Pkg\Booking\Requesting\Domain\Event\BookingRequestEventInterface;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Pkg\Booking\Requesting\Service\MailGenerator;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;
use Sdk\Shared\Dto\Mail\AttachmentDto;
use Shared\Contracts\Adapter\MailAdapterInterface;

class SendMailNotificationsListener implements DomainEventListenerInterface
{
    private const SYSTEM_EMAIL = 'info@gotostans.com';

    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly RequestRepositoryInterface $requestRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly AdministratorAdapterInterface $administratorAdapter,
        private readonly MailGenerator $mailGenerator,
        private readonly MailAdapterInterface $mailAdapter,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingRequestEventInterface);

//        $booking = $this->bookingUnitOfWork->findOrFail($event->bookingId());
        $details = $this->bookingUnitOfWork->getDetails($event->bookingId());

        $emails = [];
        if ($details instanceof HotelBooking) {
            $emails = $this->hotelAdapter->getAdministratorEmails($details->hotelInfo()->id());
        } else {
            $email = $this->supplierAdapter->getEmail($details->serviceInfo()->supplierId());
            if (!empty($email)) {
                $emails[] = $email;
            }
        }

        $administrator = $this->administratorAdapter->getManagerByBookingId($event->bookingId()->value());
        if (!empty($administrator?->email) && $this->isValidEmail($administrator->email)) {
            $emails[] = $administrator->email;
        }

        $emails[] = self::SYSTEM_EMAIL;
        $emails = array_filter($emails, fn(string|null $email) => $this->isValidEmail($email));
        if (count($emails) === 0) {
            return;
        }

        $request = $this->requestRepository->find($event->requestId());
        if ($request === null) {
            return;
        }

        $subject = $this->mailGenerator->getSubject($request);
        $body = $this->mailGenerator->generate($request);
        $attachments = [new AttachmentDto($request->file()->guid())];
        foreach ($emails as $email) {
            $this->mailAdapter->sendTo(
                $email,
                $subject,
                $body,
                $attachments,
            );
        }
    }

    private function isValidEmail(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        $pattern = '/^(?=.{1,64}@)[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*@[^-][A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,})$/';

        return preg_match($pattern, $email);
    }
}
