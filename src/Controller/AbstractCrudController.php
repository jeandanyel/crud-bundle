<?php

namespace Jeandanyel\CrudBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Jeandanyel\CrudBundle\Enum\CrudTemplatePath;
use Jeandanyel\CrudBundle\Helper\ControllerHelper;
use Jeandanyel\ListBundle\Factory\ListFactoryInterface;
use Jeandanyel\ListBundle\List\ListInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractCrudController extends AbstractController implements CrudControllerInterface
{
    protected EntityManagerInterface $entityManager;
    protected ListFactoryInterface $listFactory;
    protected TranslatorInterface $translator;

    public function list(Request $request): Response
    {
        $list = $this->createList($this->getListTypeClass());
        $controllerName = ControllerHelper::getName($this);
        $templatePath = sprintf('%s/%s', $controllerName, CrudTemplatePath::LIST->getFileName());

        if (!$this->container->get('twig')->getLoader()->exists($templatePath)) {
            $templatePath = CrudTemplatePath::LIST->value;
        }

        return $this->render($templatePath, [
            'controller_name' => $controllerName,
            'list' => $list->createView(),
        ]);
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

        $templatePath = sprintf('%s/%s', $controllerName, CrudTemplatePath::UPDATE->getFileName());

        if (!$this->container->get('twig')->getLoader()->exists($templatePath)) {
            $templatePath = CrudTemplatePath::UPDATE->value;
        }

        return $this->render($templatePath, [
            $controllerName => $entity,
            'controller_name' => $controllerName,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request): Response
    {
        $entity = $this->findEntity($request);

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new Response();
    }

    protected function createList(string $listTypeClass, array $options = []): ListInterface
    {
        return $this->listFactory->create($listTypeClass, $options);
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

    protected function getListTypeClass(): string
    {
        return ControllerHelper::getCrudControllerAttribute($this)->getListTypeClass();
    }

    #[Required]
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    #[Required]
    public function setListFactory(ListFactoryInterface $listFactory): void
    {
        $this->listFactory = $listFactory;
    }

    #[Required]
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
