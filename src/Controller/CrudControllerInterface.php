<?php

namespace Jeandanyel\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CrudControllerInterface
{
    public function list(Request $request): Response;
    public function create(Request $request): Response;
    public function update(Request $request): Response;
    public function delete(Request $request): Response;
}
