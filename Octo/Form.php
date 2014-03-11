<?php
namespace Octo;

use b8;
use b8\View;
use Octo\Template;

class Form extends b8\Form
{
    public function getView($view)
    {
        try {
            $view = Template::getPublicTemplate('Form/' . $view);
        } catch (\Exception $ex) {
            $view = new View($view, B8_PATH . 'Form/View/');
        }

        return $view;
    }

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