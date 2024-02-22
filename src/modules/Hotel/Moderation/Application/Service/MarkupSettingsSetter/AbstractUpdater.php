<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Illuminate\Support\Str;

abstract class AbstractUpdater
{
    protected function setByObjectKey(mixed $object, string $key, mixed $value): void
    {
        $setterMethod = 'set' . Str::ucfirst($key);
        if (method_exists($object, $setterMethod)) {
            $preparedValue = $value;
            $argumentType = (new \ReflectionClass($object))->getMethod($setterMethod)->getParameters()[0]?->getType(
            )?->getName();
            if (class_exists($argumentType)) {
                $preparedValue = new $argumentType($value);
            }
            $object->$setterMethod($preparedValue);
        } else {
            throw new \InvalidArgumentException("Can not update value for key [$key]");
        }
    }
}
