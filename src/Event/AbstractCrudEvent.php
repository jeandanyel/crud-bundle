<?php

namespace Jeandanyel\CrudBundle\Event;

use Jeandanyel\CrudBundle\Controller\CrudControllerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractCrudEvent extends Event
{
    public function __construct(
        protected CrudControllerInterface $controller,
        protected object $entity,
        protected ?Request $request = null,
        protected ?FormInterface $form = null,
    ) {}

    public function getController(): CrudControllerInterface
    {
        return $this->controller;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getForm(): ?FormInterface
    {
        return $this->form;
    }
}
