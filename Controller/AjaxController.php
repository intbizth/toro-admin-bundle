<?php

namespace Toro\Bundle\AdminBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;

class AjaxController extends Controller
{
    public function filterAction(Request $request, $_resource)
    {
        /** @var ResourceController $controller */
        $controller = $this->get(preg_match('/\./)', $_resource) ? $_resource : ('toro.controller.' . $_resource));

        if ($sylius = $request->attributes->get('_sylius')) {
            $parameterParser = $this->get('sylius.resource_controller.parameters_parser');
            $parameters = $parameterParser->parseRequestValues($sylius, $request);

            if (isset($sylius['grid'])) {
                if (isset($sylius['grid_filters'])) {
                    $request->query->add(['criteria' => $parameters['grid_filters']]);
                }
            }
        }

        if ($serviceSettings = $request->attributes->get('_service')) {
            $parameterParser = $this->get('sylius.resource_controller.parameters_parser');
            $parameters = $parameterParser->parseRequestValues($serviceSettings, $request);

            if($service = $this->get($parameters['name'])) {
                $data = call_user_func_array(
                    [$service, $parameters['method']],
                    isset($parameters['arguments']) ? $parameters['arguments'] : []
                );

                $view = View::create($data);
                $restViewHandler = $this->get('fos_rest.view_handler');

                return $restViewHandler->handle($view);
            }
        }

        $request->setRequestFormat('json');

        return $controller->indexAction($request);
    }

    public function transitionAction(Request $request, $id)
    {
        /** @var RepositoryInterface $repository */
        $resource = $request->get('_resource');
        $repository = $this->get('toro.repository.' . $resource);
        $graph = $request->get('_graph');
        $object = $repository->find($id);
        $uuid = str_rot13($graph . $object->getId());

        return $this->render($request->get('_template', 'ToroAdminBundle::transition.html.twig'), [
            'object' => $object,
            'graph' => $graph,
            'route' => $request->get('_apply'),
            'method' => $request->get('_method'),
            'btnCss' => $request->get('_btnCss'),
            'uuid' => $uuid,
        ]);
    }
}
