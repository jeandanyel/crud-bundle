<?php

namespace Jeandanyel\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

interface CrudControllerInterface
{
    public function list(): Response;
    public function create(): Response;
    public function update(): Response;
    public function delete(): Response;
}