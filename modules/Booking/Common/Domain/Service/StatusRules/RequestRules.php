<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Entity\CancelRequestableInterface;
use Module\Booking\Common\Domain\Entity\ChangeRequestableInterface;
use Module\Booking\Common\Domain\Exception\BookingTypeDoesntHaveDocumentGenerator;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\CancellationRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ChangeRequestGenerator;
use Module\Booking\Hotel\Domain\Service\DocumentGenerator\ReservationRequestGenerator;

class RequestRules implements RequestRulesInterface
{
    /**
     * @var array<int, BookingStatusEnum> $transitions
     */
    protected array $transitions = [];

    public function __construct()
    {
        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::WAITING_CANCELLATION);
        //@todo тут раньше было WAITING_CONFIRMATION
        $this->addTransition(BookingStatusEnum::WAITING_PROCESSING, BookingStatusEnum::REGISTERED);
        $this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::REGISTERED);
    }

    public function isRequestableStatus(BookingStatusEnum $status): bool
    {
        return array_key_exists($status->value, $this->transitions);
    }

    /**
     * @param BookingStatusEnum $status
     * @return BookingStatusEnum
     * @throws NotRequestableStatus
     */
    public function getNextStatus(BookingStatusEnum $status): BookingStatusEnum
    {
        $nextStatus = $this->transitions[$status->value] ?? null;
        if ($nextStatus === null) {
            throw new NotRequestableStatus("Status [{$status->value}] not requestable.");
        }
        return $nextStatus;
    }

    public function getDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->status()) {
            BookingStatusEnum::CONFIRMED => $this->getCancelDocumentGenerator($booking),
            BookingStatusEnum::WAITING_PROCESSING => $this->getChangeDocumentGenerator($booking),
            BookingStatusEnum::PROCESSING => $this->getBookingDocumentGenerator($booking),
            default => throw new NotRequestableStatus("Status [{$booking->status()->value}] not requestable.")
        };
    }

    private function getCancelDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new CancellationRequestGenerator(),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getChangeDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new ChangeRequestGenerator(),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function getBookingDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new ReservationRequestGenerator(),
            default => throw new BookingTypeDoesntHaveDocumentGenerator()
        };
    }

    private function addTransition(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): void
    {
        $this->transitions[$fromStatus->value] = $toStatus;
    }
}
