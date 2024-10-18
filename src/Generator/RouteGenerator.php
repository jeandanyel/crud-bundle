<?php

namespace Jeandanyel\CrudBundle\Generator;

use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;
use Jeandanyel\CrudBundle\Enum\CrudRoutePath;
use Jeandanyel\CrudBundle\Helper\ControllerHelper;
use Symfony\Component\Routing\Route;

class RouteGenerator implements RouteGeneratorInterface {
    public function generate(CrudControllerInterface $controller, string $methodName): Route
    {
        $defaults = ['_controller' => sprintf('%s::%s', $controller::class, $methodName)];
        $path = $this->getPath($controller, $methodName);
        
        return new Route($path, $defaults);
    }

    public function getName(CrudControllerInterface $controller, string $methodName): string
    {
        $controllerName = ControllerHelper::getName($controller);

        return sprintf('crud_%s_%s', $controllerName, $methodName);
    }

    private function getPath(CrudControllerInterface $controller, string $methodName): string
    {        
        $controllerName = ControllerHelper::getName($controller);

        return sprintf('/%s%s', $controllerName, CrudRoutePath::get($methodName));
    }
}