<?php

namespace Jeandanyel\CrudBundle\Attribute;

use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CrudController
{
    public function __construct(
        public string $entityClass,
        public string $formTypeClass
    ) {
        // TODO: fix this...
        // if (!is_subclass_of(get_called_class(), CrudControllerInterface::class)) {
        //     throw new \InvalidArgumentException(sprintf('The annotation can only be used on classes implementing %s', CrudControllerInterface::class));
        // }
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getFormTypeClass(): string
    {
        return $this->formTypeClass;
    }
}
