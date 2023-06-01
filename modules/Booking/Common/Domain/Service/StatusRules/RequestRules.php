<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Entity\CancelRequestableInterface;
use Module\Booking\Common\Domain\Entity\ChangeRequestableInterface;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

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
            BookingStatusEnum::CONFIRMED => $booking->getCancelRequestDocumentGenerator(),
            BookingStatusEnum::WAITING_PROCESSING => $booking->getChangeRequestDocumentGenerator(),
            BookingStatusEnum::PROCESSING => $booking->getBookingRequestDocumentGenerator(),
            default => throw new NotRequestableStatus("Status [{$booking->status()->value}] not requestable.")
        };
    }

    private function addTransition(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): void
    {
        $this->transitions[$fromStatus->value] = $toStatus;
    }
}
