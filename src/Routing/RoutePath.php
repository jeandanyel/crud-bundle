<?php

namespace Jeandanyel\Routing;

class RoutePath {
    public const LIST = '/list';
    public const CREATE = '/create';
    public const UPDATE = '/update/{id}';
    public const DELETE = '/delete/{id}';

    public static function get(string $constantName): string
    {
        static $reflectionClass = new \ReflectionClass(self::class);

        $constantName = strtoupper($constantName);

        if (!$reflectionClass->hasConstant($constantName)) {
            throw new \InvalidArgumentException("Constant {$constantName} does not exist in " . self::class);
        }

        return $reflectionClass->getConstant($constantName);
    }
}