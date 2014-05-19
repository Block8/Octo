<?php
namespace Octo\Admin;

use b8;
use b8\View;
use Octo\Admin\Template;

/**
 * Class Form
 * @package Octo\Admin
 */
class Form extends b8\Form
{

    /**
     * Get the correct view for the form
     *
     * @param $view
     * @return View|null|Template
     */
    public function getView($view)
    {
        $template = Template::getAdminTemplate('Form/' . $view, 'System');
        if ($template) {
            $view = Template::getAdminTemplate('Form/' . $view, 'System');
            return $view;
        }

        return new View($view, B8_PATH . 'Form/View/');
    }
}
