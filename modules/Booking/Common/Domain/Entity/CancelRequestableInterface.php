<?php

namespace Module\Booking\Common\Domain\Entity;

use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;

interface CancelRequestableInterface extends ReservationInterface
{
    public function getCancelRequestDocumentGenerator(): DocumentGeneratorInterface;
}
