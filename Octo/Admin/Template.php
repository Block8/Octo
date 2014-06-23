<?php

namespace Octo\Admin;

class Template extends \Octo\Template
{

    /**
     * Type of template to load
     *
     * @var string
     */
    protected static $templateType = 'admin_templates';

    /**
     * Load an admin template
     *
     * @param $template
     * @param null $module
     * @return null|Template
     */
    public static function getAdminTemplate($template, $module = null)
    {
        $rtn = parent::getAdminTemplate($template, $module);

        if (!is_null($rtn)) {
            $rtn->addFunction('hash', function ($args, $view) {
                    return md5($view->getVariable($args['value']));
            });
        }

        return $rtn;
    }
}
