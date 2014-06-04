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
        if (Template::exists('Form/' . $view)) {
            return Template::getAdminTemplate('Form/' . $view, 'System');
        }

        return new View($view, B8_PATH . 'Form/View/');
    }
}
