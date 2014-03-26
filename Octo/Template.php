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

    public static function exists($template, $module = null)
    {
        if (!empty($module)) {
            var_dump(debug_backtrace());
        }

        if (!is_null(static::getPath($template, $module))) {
            return true;
        }

        return false;
    }

    public static function getPath($template, $module = null)
    {
        $config = Config::getInstance();
        $paths = array_reverse($config->get('Octo.paths.' . static::$templateType, []));

        foreach ($paths as $moduleName => $path) {
            if (!is_null($module) && $moduleName !== $module) {
                $path .= $module . '/';
            }

            if (file_exists($path . $template . '.html')) {
                return $path;
            }
        }

        return null;
    }

    public static function getAdminTemplate($template, $module = null)
    {
        $rtn = null;

        $path = AdminTemplate::getPath($template, $module);

        if (!is_null($path)) {
            $templateSrc = file_get_contents($path . $template . '.html');
            $rtn = new AdminTemplate($templateSrc);
            Event::trigger('AdminTemplateLoaded', $rtn);

            return $rtn;
        }

        throw new \Exception('Template does not exist: ' . $template);
    }

    public static function getPublicTemplate($template, $module = null)
    {
        $rtn = null;

        $path = PublicTemplate::getPath($template, $module);

        if (!is_null($path)) {
            $templateSrc = file_get_contents($path . $template . '.html');
            $rtn = new PublicTemplate($templateSrc);
            Event::trigger('PublicTemplateLoaded', $rtn);

            return $rtn;
        }

        throw new \Exception('Template does not exist: ' . $template);
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
