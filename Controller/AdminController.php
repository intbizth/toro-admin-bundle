<?php

namespace Toro\Bundle\AdminBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AdminController extends ResourceController
{
    // todo: create new bundle for manage this feature.

    /**
     * @param Request $request
     * @param null $path
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activeStateAction(Request $request, $path)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        $resource = $this->findOr404($configuration);

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);

        if ($event->isStopped() && !$configuration->isHtmlRequest()) {
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        if ($event->isStopped()) {
            $this->flashHelper->addFlashFromEvent($configuration, $event);

            return $this->redirectHandler->redirectToResource($configuration, $resource);
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($resource, $path, true);

        $this->repository->bulkUpdate(array($path => false));
        $this->manager->flush();

        $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($resource, 200));
        }

        $this->flashHelper->addSuccessFlash($configuration, ResourceActions::UPDATE, $resource);

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }
}
