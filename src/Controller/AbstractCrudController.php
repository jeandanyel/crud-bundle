<?php

namespace Jeandanyel\CrudBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Jeandanyel\CrudBundle\Enum\CrudTemplate;
use Jeandanyel\CrudBundle\Helper\ControllerHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCrudController extends AbstractController implements CrudControllerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TranslatorInterface $translator,
    ) {}

    public function list(Request $request): Response
    {
        return new Response();
    }

    public function create(Request $request): Response
    {
        return new Response();
    }

    public function update(Request $request): Response
    {
        $entity = $this->findEntity($request);
        $controllerName = ControllerHelper::getName($this);
        $form = $this->createForm($this->getFormTypeClass(), $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans(
                "{$controllerName}.messages.has_been_updated",
                ['%name%' => $entity]
            ));

            return $this->redirectToRoute("crud_{$controllerName}_edit", ['id' => $entity->getId()]);
        }

        $template = sprintf('%s/%s', $controllerName, CrudTemplate::UPDATE->getFileName());

        if (!$this->container->get('twig')->getLoader()->exists($template)) {
            $template = CrudTemplate::UPDATE->value;
        }

        return $this->render($template, [
            $controllerName => $entity,
            'controller_name' => $controllerName,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request): Response
    {
        $entity = $this->findEntity($request);

        return new Response();
    }

    protected function findEntity(Request $request): object
    {
        $id = $request->attributes->get('id');
        $entity = $this->getRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(sprintf(
                'The %s entity with ID %d was not found.',
                $this->getEntityClass(),
                $id
            ));
        }

        return $entity;
    }

    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntityClass());
    }

    protected function getEntityClass(): string
    {
        return ControllerHelper::getCrudControllerAttribute($this)->getEntityClass();
    }

    protected function getFormTypeClass(): string
    {
        return ControllerHelper::getCrudControllerAttribute($this)->getFormTypeClass();
    }
}
