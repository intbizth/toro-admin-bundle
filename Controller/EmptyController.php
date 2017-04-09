<?php

namespace Toro\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class EmptyController
{
    /**
     * @return Response
     */
    public function renderAction()
    {
        return Response::create('');
    }
}
