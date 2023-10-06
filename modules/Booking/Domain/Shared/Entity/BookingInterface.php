<?php

namespace Module\Booking\Domain\Shared\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Exception\NotRequestableEntity;
use Module\Booking\Domain\Shared\Exception\NotRequestableStatus;
use Module\Booking\Domain\BookingRequest\Service\RequestCreator;
use Module\Booking\Domain\Shared\Service\RequestRules;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Shared\Domain\Entity\EntityInterface;

interface BookingInterface extends EntityInterface
{
    public function id(): BookingId;

    public function orderId(): OrderId;

    public function status(): BookingStatusEnum;

    public function createdAt(): CarbonImmutable;

    public function creatorId(): CreatorId;

    public function price(): BookingPrice;

    /**
     * @param RequestRules $requestRules
     * @return void
     * @throws NotRequestableStatus
     * @throws NotRequestableEntity
     */
    public function generateRequest(RequestRules $requestRules, RequestCreator $requestCreator): void;

    public function isManualGrossPrice(): bool;

    public function isManualNetPrice(): bool;

}
