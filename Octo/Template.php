<?php

namespace Octo;

use b8\Config;
use b8\View;
use Octo\Template as PublicTemplate;
use Octo\Admin\Template as AdminTemplate;
use Octo\Event;

class Template extends View\Template
{
    protected static $templateType = 'templates';

    public static function exists($template)
    {
        if (!is_null(static::getPath($template))) {
            return true;
        }

        return false;
    }

    public static function getPath($template)
    {
        $config = Config::getInstance();
        $paths = array_reverse($config->get('Octo.paths.' . static::$templateType, []));

        foreach ($paths as $path) {
            if (file_exists($path . $template . '.html')) {
                return $path;
            }
        }

        return null;
    }

    public static function getAdminTemplate($template)
    {
        $rtn = null;

        if (AdminTemplate::exists($template)) {
            $rtn = AdminTemplate::createFromFile($template, AdminTemplate::getPath($template));
            Event::trigger('AdminTemplateLoaded', $rtn);
        } else {
            throw new \Exception('Template does not exist: ' . $template);
        }

        return $rtn;
    }

    public static function getPublicTemplate($template)
    {
        $rtn = null;

        if (PublicTemplate::exists($template)) {
            $rtn = PublicTemplate::createFromFile($template, PublicTemplate::getPath($template));
            Event::trigger('PublicTemplateLoaded', $rtn);
        } else {
            throw new \Exception('Template does not exist: ' . $template);
        }

        return $rtn;
    }

    public function includeTemplate($args, $view)
    {
        if ($view instanceof AdminTemplate) {
            $template = static::getAdminTemplate($view->getVariable($args['template']));
        } else {
            $template = static::getPublicTemplate($view->getVariable($args['template']));
        }

        if (isset($args['variables'])) {
            if (!is_array($args['variables'])) {
                $args['variables'] = array($args['variables']);
            }

            foreach ($args['variables'] as $variable) {

                $variable = explode('=>', $variable);
                $variable = array_map('trim', $variable);

                if (count($variable) == 1) {
                    $template->{$variable[0]} = $view->getVariable($variable[0]);
                } else {
                    $template->{$variable[1]} = $view->getVariable($variable[0]);
                }
            }
        }

        return $template->render();
    }
}
