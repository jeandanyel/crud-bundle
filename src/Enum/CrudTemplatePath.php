<?php

namespace Jeandanyel\CrudBundle\Enum;

enum CrudTemplatePath: string
{
    case LIST = '@JeandanyelCrud/crud/list.html.twig';
    case CREATE = '@JeandanyelCrud/crud/create.html.twig';
    case UPDATE = '@JeandanyelCrud/crud/update.html.twig';
    case DELETE = '@JeandanyelCrud/crud/delete.html.twig';

    public function getFileName(): string
    {
        return basename($this->value);
    }
}