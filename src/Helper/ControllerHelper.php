<?php

namespace Jeandanyel\CrudBundle\Helper;

use Jeandanyel\CrudBundle\Attribute\CrudController;
use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class ControllerHelper {
    public static function getName(CrudControllerInterface $controller): string
    {
        static $name = [];

        if (empty($name[$controller::class])) {
            $reflectionClass = ControllerHelper::getReflectionClass($controller);
            $converter = new CamelCaseToSnakeCaseNameConverter();
            $name[$controller::class] = $converter->normalize(str_replace('Controller', '', $reflectionClass->getShortName()));
        }

        return $name[$controller::class];
    }

    public static function getReflectionClass(CrudControllerInterface $controller): \ReflectionClass
    {
        static $reflections = [];

        if (empty($reflections[$controller::class])) {
            $reflections[$controller::class] = new \ReflectionClass($controller);
        }

        return $reflections[$controller::class];
    }

    public static function getCrudControllerAttribute(CrudControllerInterface $controller): CrudController
    {
        static $attributes = [];

        if (empty($attributes[$controller::class])) {
            $reflectionClass = new \ReflectionClass($controller);
            $attribute = $reflectionClass->getAttributes(CrudController::class)[0];
            $attributes[$controller::class] = $attribute->newInstance();;
        }

        return $attributes[$controller::class];
    }
}
