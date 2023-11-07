<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Carbon\CarbonPeriod;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Str;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;

abstract class AbstractEditor
{
    protected function setField(ServiceDetailsInterface $details, string $field, mixed $value): void
    {
        $setterMethod = $this->getFieldSetter($field);
        if (!method_exists($details, $setterMethod)) {
            throw new \RuntimeException('Details doesn\'t has [' . $field . '] field setter');
        }
        $preparedValue = $value;
        $reflectionMethod = new \ReflectionMethod($details, $setterMethod);
        $valueType = $reflectionMethod->getParameters()[0]->getType()->getName();
        if ($valueType === DateTimeInterface::class) {
            try {
                $preparedValue = new DateTime($preparedValue);
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException('Invalid datetime value');
            }
        }
        if ($valueType === BookingPeriod::class && $value !== null) {
            $preparedValue = BookingPeriod::fromCarbon(new CarbonPeriod($value['dateFrom'], $value['dateTo']));
        }
        $details->$setterMethod($preparedValue);
    }

    private function getFieldSetter(string $field): string
    {
        return 'set' . Str::ucfirst($field);
    }
}
