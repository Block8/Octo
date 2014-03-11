<?php
namespace Octo\Admin;

use b8;
use b8\View;

class Form extends b8\Form
{
    public function getView($view)
    {
        return new View($view, CMS_PATH . 'View/Form/');
    }
}