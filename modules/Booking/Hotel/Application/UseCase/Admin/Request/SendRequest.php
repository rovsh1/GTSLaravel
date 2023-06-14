<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Request;

use Module\Booking\Common\Application\Support\UseCase\Admin\Request\SendRequest as Base;
use Module\Booking\Common\Domain\Service\RequestCreator;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class SendRequest extends Base
{
    public function __construct(
        BookingRepository $repository,
        DomainEventDispatcherInterface $eventDispatcher,
        RequestCreator $requestCreator
    ) {
        parent::__construct($repository, $eventDispatcher, $requestCreator);
    }
}
