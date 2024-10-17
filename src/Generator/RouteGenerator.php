<?php

namespace Jeandanyel\CrudBundle\Generator;

use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;
use Jeandanyel\Routing\RoutePath;
use Symfony\Component\Routing\Route;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class RouteGenerator implements RouteGeneratorInterface {
    public function generate(CrudControllerInterface $controller, string $methodName): Route
    {
        $defaults = ['_controller' => sprintf('%s::%s', $controller::class, $methodName)];
        $path = $this->getPath($controller, $methodName);
        
        return new Route($path, $defaults);
    }

    public function getName(CrudControllerInterface $controller, string $methodName): string
    {
        return sprintf('crud_%s_%s', $this->getPrefix($controller), $methodName);
    }

    private function getPath(CrudControllerInterface $controller, string $methodName): string
    {        
        return sprintf('/%s%s', $this->getPrefix($controller), RoutePath::get($methodName));
    }

    private function getPrefix(CrudControllerInterface $controller): string
    {
        static $prefix = [];

        if (empty($prefix[$controller::class])) {
            $reflectionClass = $this->getReflectionClass($controller);
            $converter = new CamelCaseToSnakeCaseNameConverter();
            $prefix[$controller::class]= $converter->normalize(str_replace('Controller', '', $reflectionClass->getShortName()));
        }

        return $prefix[$controller::class];
    }

    private function getReflectionClass(CrudControllerInterface $controller): \ReflectionClass
    {
        static $reflections = [];

        if (empty($reflections[$controller::class])) {
            $reflections[$controller::class] = new \ReflectionClass($controller);
        }

        return $reflections[$controller::class];
    }
}