<?php
namespace Octo\Admin;

use b8;
use b8\View;
use Octo\Admin\Template;

class Form extends b8\Form
{
    public function getView($view)
    {
        if (Template::exists('Form/' . $view)) {
            $view = Template::getAdminTemplate('Form/' . $view);
            return $view;
        }

        return new View($view, B8_PATH . 'Form/View/');
    }
}