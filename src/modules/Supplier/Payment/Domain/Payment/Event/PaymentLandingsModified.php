<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Domain\Payment\Event;


use Module\Supplier\Payment\Domain\Payment\Payment;
use Module\Supplier\Payment\Domain\Payment\ValueObject\LandingCollection;

class PaymentLandingsModified implements PaymentEventInterface
{
    public function __construct(
        public readonly Payment $payment,
        public readonly LandingCollection $landings,
        public readonly LandingCollection $oldLandings,
    ) {}
}
