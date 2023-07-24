<?php

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Exception\NotRequestableEntity;
use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\Service\InvoiceCreator;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Common\Domain\Service\RequestRules;
use Module\Booking\Common\Domain\Service\VoucherCreator;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;

interface BookingInterface extends EntityInterface
{
    public function orderId(): Id;

    public function type(): BookingTypeEnum;

    public function status(): BookingStatusEnum;

    public function createdAt(): CarbonImmutable;

    public function creatorId(): Id;

    public function price(): BookingPrice;

    /**
     * @param RequestRules $requestRules
     * @return void
     * @throws NotRequestableStatus
     * @throws NotRequestableEntity
     */
    public function generateRequest(RequestRules $requestRules, RequestCreator $requestCreator): void;

    public function generateVoucher(VoucherCreator $voucherCreator): void;

    public function generateInvoice(InvoiceCreator $invoiceCreator): void;

    public function canSendClientVoucher(): bool;

}
