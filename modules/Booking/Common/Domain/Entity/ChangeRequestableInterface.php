<?php

namespace Module\Booking\Common\Domain\Entity;

use Module\Booking\Common\Domain\Service\DocumentGenerator\DocumentGeneratorInterface;

interface ChangeRequestableInterface extends ReservationInterface
{
    public function getChangeRequestDocumentGenerator(): DocumentGeneratorInterface;
}
