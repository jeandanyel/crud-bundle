<?php

namespace Jeandanyel\CrudBundle\Routing;

use Jeandanyel\CrudBundle\Generator\RouteGeneratorInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{
    private const ROUTE_TYPE = 'crud';

    private bool $isLoaded = false;

    public function __construct(
        private RouteGeneratorInterface $routeGenerator,
        private RewindableGenerator $controllers,
        ?string $env = null
    ) {
        parent::__construct($env);
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if ($this->isLoaded) {
            throw new \RuntimeException('Do not add the "crud" loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);

            foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
                if (!in_array($reflectionMethod->getName(), ['list', 'create', 'update', 'delete'])) {
                    continue;
                }

                $attributes = $reflectionMethod->getAttributes(Route::class);

                if (empty($attributes)) {
                    $route = $this->routeGenerator->generate($controller, $reflectionMethod->getName());
                    $routeName = $this->routeGenerator->getName($controller, $reflectionMethod->getName());

                    $routes->add($routeName, $route);
                }
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $type === self::ROUTE_TYPE;
    }
}