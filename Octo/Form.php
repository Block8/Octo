<?php
namespace Octo;

use b8;
use b8\Config;
use b8\View;
use Octo\Template;

/**
 * Class Form
 * @package Octo
 */
class Form extends b8\Form
{
    protected $validation = true;

    public function enableValidation()
    {
        $this->validation = true;
    }

    public function disableValidation()
    {
        $this->validation = false;
    }

    /**
     * Get the view for the form
     *
     * @param $view
     * @return View|Template
     */
    public function getView($view)
    {
        try {
            return new Template('Form/' . $view);
        } catch (\Exception $ex) {
            return new View($view, B8_PATH . 'Form/View/');
        }
    }

    protected function onPreRender(&$view)
    {
        /** @var \Octo\AssetManager $assets */
        $assets = Config::getInstance()->get('Octo.AssetManager');

        if ($this->validation) {
            $view->validation = true;
            $assets->addJs('Forms', 'validation');
        }

        parent::onPreRender($view);
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
