<?php

namespace Module\Shared\Infrastructure\Facade;

use Spatie\LaravelData\Normalizers\Normalizer;

class DomainModelNormalizer implements Normalizer
{
    public function normalize(mixed $value): ?array
    {
        if (!is_object($value)) {
            return null;
        }
        $data = get_object_vars($value);
        if (!empty($data)) {
            return $data;
        }
        $object = new \ReflectionObject($value);
        $props = $object->getProperties();
        foreach ($props as $property) {
            $propertyName = $property->getName();
            if (method_exists($value, $propertyName)) {
                $data[$propertyName] = $value->$propertyName();
            }
        }
        return $data;
    }
}
