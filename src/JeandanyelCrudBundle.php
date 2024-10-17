<?php

namespace Jeandanyel\CrudBundle;

use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class JeandanyelCrudBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $builder->registerForAutoconfiguration(CrudControllerInterface::class)
            ->addTag('crud.controller')
        ;
    }
}