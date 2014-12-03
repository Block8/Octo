<?php

namespace Octo\Form\Element;

use b8\Form\Element\Select;
use b8\View;

class MultiplePicker extends Select
{
    /**
     * @var array Array of items
     */
    protected $items = [];

    /**
     * @param null $viewFile
     * @return string
     */
    public function render($viewFile = null)
    {
        unset($viewFile);
        return parent::render('MultiplePicker');
    }

    /**
     * @param View $view
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);
    }
}
