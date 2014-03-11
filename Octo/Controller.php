<?php

namespace Octo;

abstract class Controller extends \b8\Controller
{
    /**
     * b8 framework requires that controllers have an init() method
     */
    public function init()
    {

    }

    public function handleAction($action, $params)
    {
        $output = parent::handleAction($action, $params);
        $this->response->setContent($output);

        return $this->response;
    }
}
