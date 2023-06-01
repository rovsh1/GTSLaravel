<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

interface RequestRulesInterface
{
    public function isRequestableStatus(BookingStatusEnum $status): bool;

    /**
     * @param BookingStatusEnum $status
     * @return BookingStatusEnum
     * @throws NotRequestableStatus
     */
    public function getNextStatus(BookingStatusEnum $status): BookingStatusEnum;

    public function getDocumentGenerator(BookingRequestableInterface $booking): DocumentGeneratorInterface;
}
