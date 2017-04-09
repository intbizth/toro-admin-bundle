<?php

namespace Toro\Bundle\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class RedirectController
{
    /**
     * @return Response
     */
    public function reloadAction()
    {
        return Response::create('<script>window.location.reload()</script>');
    }
}
