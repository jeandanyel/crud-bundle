<?php

namespace Jeandanyel\CrudBundle\Generator;

use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;
use Symfony\Component\Routing\Route;

interface RouteGeneratorInterface {
    public function generate(CrudControllerInterface $controller, string $methodName): Route;
    public function getName(CrudControllerInterface $controller, string $methodName): string;
}