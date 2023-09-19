<?php

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\VoucherCreator;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Shared\Domain\Entity\EntityInterface;

interface BookingInterface extends EntityInterface
{
    public function id(): BookingId;

    public function orderId(): OrderId;

    public function type(): BookingTypeEnum;

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

    public function generateVoucher(VoucherCreator $voucherCreator): void;

    public function canSendClientVoucher(): bool;

    public function isManualGrossPrice(): bool;

    public function isManualNetPrice(): bool;

}
