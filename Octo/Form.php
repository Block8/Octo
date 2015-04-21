<?php
namespace Octo;

use b8;
use b8\View;
use Octo\Html\Template;

/**
 * Class Form
 * @package Octo
 */
class Form extends b8\Form
{

    /**
     * Get the view for the form
     *
     * @param $view
     * @return View|null|Template
     */
    public function getView($view)
    {
        if (Template::exists('Form/' . $view)) {
            $view = Template::load('Form/' . $view);
            return $view;
        }

        return new View($view, B8_PATH . 'Form/View/');
    }

    /**
     * Get the correct field class for the form
     *
     * @param $type
     * @return null|string
     */
    public static function getFieldClass($type)
    {
        $config = b8\Config::getInstance();

        $try = '\\' . $config->get('site.namespace') . '\\Form\\Element\\' . $type;

        if (class_exists($try)) {
            return $try;
        }

        $try = '\\Octo\\Form\\Element\\' . $type;

        if (class_exists($try)) {
            return $try;
        }

        $try = '\\b8\\Form\\Element\\' . $type;

        if (class_exists($try)) {
            return $try;
        }

        return null;
    }
}
