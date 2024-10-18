<?php

namespace Jeandanyel\CrudBundle\Enum;

enum CrudRoutePath: string 
{
    case LIST = '/list';
    case CREATE = '/create';
    case UPDATE = '/update/{id}';
    case DELETE = '/delete/{id}';

    public static function get(string $pathName): string
    {
        foreach (self::cases() as $case) {
            if ($case->name === strtoupper($pathName)) {
                return $case->value;
            }
        }

        throw new \InvalidArgumentException("Case {$pathName} does not exist in " . self::class);
    }
}