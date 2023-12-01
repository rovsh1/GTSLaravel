<?php

namespace Sdk\Booking\Support\Entity;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\DetailsId;
use Sdk\Booking\ValueObject\ServiceInfo;

abstract class AbstractServiceDetails extends AbstractDetails
{
    public function __construct(
        DetailsId $id,
        BookingId $bookingId,
        protected readonly ServiceInfo $serviceInfo,
    ) {
        parent::__construct($id, $bookingId);
    }

    public function id(): DetailsId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function serviceInfo(): ServiceInfo
    {
        return $this->serviceInfo;
    }
}