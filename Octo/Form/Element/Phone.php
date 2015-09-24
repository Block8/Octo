<?php
namespace Octo\Form\Element;

use b8\Config;
use b8\Form\Element\Text;

class Phone extends Text
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setPattern('([0-9 ]+)');
    }

    public function setValue($value)
    {
        $value = preg_replace('/([^0-9])/', '', $value);
        parent::setValue($value);
    }

    protected function onPreRender(&$view)
    {
        parent::onPreRender($view);

        /** @var \Octo\AssetManager $assets */
        $assets = Config::getInstance()->get('Octo.AssetManager');
        $assets->addJs('Forms', 'phoneformat');
        $view->type = 'tel';
    }
}
