<?php

namespace Toro\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class DashboardController
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @param EngineInterface $templatingEngine
     */
    public function __construct(EngineInterface $templatingEngine)
    {
        $this->templatingEngine = $templatingEngine;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->templatingEngine->renderResponse('ToroAdminBundle:Dashboard:index.html.twig');
    }
}
