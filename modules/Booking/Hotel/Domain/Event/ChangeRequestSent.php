<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\Event\RequestEventInterface;
use Module\Booking\Common\Domain\Event\StatusEventInterface;

class ChangeRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{

}
