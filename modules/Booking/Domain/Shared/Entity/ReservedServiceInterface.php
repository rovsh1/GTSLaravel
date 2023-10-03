<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Entity;

use Carbon\CarbonInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\ServiceInfoInterface;

interface ReservedServiceInterface
{
    public function id(): mixed;

    //ID номера отеля или услуги + название номера/услуги + ID поставщика (отеля)
    public function serviceInfo(): ServiceInfoInterface;

    //условия брони - резидент/не резидент
    public function conditions();

    public function type();

    public function dateStart(): CarbonInterface;

    public function dateEnd(): ?CarbonInterface;

    public function note(): ?string;

    //тут должна быть цена за услугу, итоговая цена и расчет (все цены гросс)
    public function price(): BookingPrice;
}
