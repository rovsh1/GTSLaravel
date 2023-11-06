<?php

namespace Module\Booking\Domain\Shared\Service\StatusRules;

use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Shared\Enum\Booking\BookingStatusEnum;

class AdministratorRules extends AbstractRules implements StatusRulesInterface
{
    public function __construct(
        private readonly RequestRules $requestRules
    ) {
        $this->addTransition(BookingStatusEnum::DRAFT, BookingStatusEnum::CREATED);

        $this->addTransition(BookingStatusEnum::CREATED, BookingStatusEnum::PROCESSING);
        $this->addTransition(BookingStatusEnum::CREATED, BookingStatusEnum::CANCELLED);

        $this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::CANCELLED);
        //$this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);

        $this->addTransition(BookingStatusEnum::WAITING_CONFIRMATION, BookingStatusEnum::CONFIRMED);
        $this->addTransition(BookingStatusEnum::WAITING_CONFIRMATION, BookingStatusEnum::NOT_CONFIRMED);

        //$this->addTransition(BookingStatusEnum::NOT_CONFIRMED, BookingStatusEnum::WAITING_CONFIRMATION);
        $this->addTransition(BookingStatusEnum::NOT_CONFIRMED, BookingStatusEnum::CANCELLED);

        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::WAITING_PROCESSING);
//        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::WAITING_CANCELLATION);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED_FEE);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED_NO_FEE);

        //$this->addTransition(BookingStatusEnum::WAITING_PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);

        //$this->addTransition(BookingStatusEnum::INVOICED, BookingStatusEnum::WAITING_CANCELLATION);

        $this->addTransition(BookingStatusEnum::WAITING_CANCELLATION, BookingStatusEnum::CANCELLED);
        $this->addTransition(BookingStatusEnum::WAITING_CANCELLATION, BookingStatusEnum::CANCELLED_FEE);
        $this->addTransition(BookingStatusEnum::WAITING_CANCELLATION, BookingStatusEnum::CANCELLED_NO_FEE);
    }

    public function isEditableStatus(BookingStatusEnum $status): bool
    {
        return $status === BookingStatusEnum::CREATED
            || ($this->requestRules->isRequestableStatus($status) && $status !== BookingStatusEnum::CONFIRMED);
    }

    public function isCancelledStatus(BookingStatusEnum $status): bool
    {
        return in_array($status, [
            BookingStatusEnum::CANCELLED,
            BookingStatusEnum::CANCELLED_FEE,
            BookingStatusEnum::CANCELLED_NO_FEE,
        ]);
    }

    public function isDeletedStatus(BookingStatusEnum $status): bool
    {
        return $status == BookingStatusEnum::DELETED;
    }

    public function canEditExternalNumber(BookingStatusEnum $status): bool
    {
        return $status === BookingStatusEnum::CONFIRMED;
    }

    public function canChangeRoomPrice(BookingStatusEnum $status): bool
    {
        return in_array($status, [
            BookingStatusEnum::CREATED,
            BookingStatusEnum::PROCESSING,
            BookingStatusEnum::WAITING_PROCESSING,
            BookingStatusEnum::NOT_CONFIRMED,
        ]);
    }

    /**
     * @return BookingStatusEnum[]
     */
    public static function getCompletedStatuses(): array
    {
        return [
            BookingStatusEnum::CONFIRMED,
            BookingStatusEnum::CANCELLED_FEE,
            BookingStatusEnum::CANCELLED_NO_FEE
        ];
    }
}
