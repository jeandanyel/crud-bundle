<?php

namespace Jeandanyel\CrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractCrudController extends AbstractController implements CrudControllerInterface
{
    public function list(): Response
    {
        return new Response();
    }

    public function create(): Response
    {
        return new Response();
    }

    public function update(): Response
    {
        return new Response();
    }

    public function delete(): Response
    {
        return new Response();
    }
}