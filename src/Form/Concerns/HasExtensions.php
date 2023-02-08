<?php

namespace Gsdk\Form\Concerns;

use Gsdk\Form\Element\Input;
use Gsdk\Form\ElementInterface;

trait HasExtensions
{
    private static string $defaultNamespace = 'Gsdk\Form\Element\\';

    private static array $extendedNamespaces = [];

    private static array $extendedElements = [];

    private static array $aliases = [
        'phone' => 'tel',
    ];

    private static array $inputTypes = ['email', 'color', 'date', 'file', 'hidden', 'image', 'month', 'number', 'password', 'range', 'reset', 'search', 'tel', 'text', 'time', 'url', 'week'];

    public static function registerNamespace(string $namespace): void
    {
        self::$extendedNamespaces[] = $namespace;
    }

    public static function extend(string $type, string $class): void
    {
        self::$extendedElements[$type] = $class;
    }

    public static function alias(string $name, string $alias): void
    {
        self::$aliases[$alias] = $name;
    }

    public static function elementFactory(string $name, string $type, $options): ?ElementInterface
    {
        if (isset(self::$aliases[$type])) {
            $type = self::$aliases[$type];
        }

        $class = self::getExtendedClass($type)
            ?? self::getDefaultClass($type)
            ?? self::getClassFromType($type);
        if (!$class) {
            throw new \Exception('Element type [' . $type . '] not defined');
        }

        if ($class === Input::class) {
            $options = array_merge($options, ['inputType' => $type]);
        }

        return new $class($name, $options);
    }

    private static function getExtendedClass(string $type): ?string
    {
        foreach (self::$extendedNamespaces as $ns) {
            $class = self::getClassInNamespace($ns, $type);
            if ($class) {
                return $class;
            }
        }
        return null;
    }

    private static function getDefaultClass(string $type): ?string
    {
        return self::getClassInNamespace(self::$defaultNamespace, $type);
    }

    private static function getClassFromType(string $type): ?string
    {
        return in_array($type, self::$inputTypes) ? Input::class : null;
    }

    private static function getClassInNamespace(string $namespace, string $type): ?string
    {
        $class = $namespace . ucfirst($type);
        if (!class_exists($class)) {
            return null;
        }

        self::extend($type, $class);

        return $class;
    }
}
