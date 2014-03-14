<?php

namespace Octo;

use b8\Config;
use b8\View;
use Octo\Admin\Template as AdminTemplate;
use Octo\Event;

class Template extends View\Template
{
    public static function getAdminTemplate($template)
    {
        if (AdminTemplate::exists($template, static::getSitePath())) {
            $rtn = AdminTemplate::createFromFile($template, static::getSitePath());
        } else {
            $rtn = AdminTemplate::createFromFile($template, static::getSystemPath());
        }

        Event::trigger('AdminTemplateLoaded', $rtn);

        return $rtn;
    }

    public static function getPublicTemplate($template)
    {
        if (static::exists($template, static::getSitePath('Template'))) {
            $rtn = static::createFromFile($template, static::getSitePath('Template'));
        } else {
            $rtn = static::createFromFile($template, static::getSystemPath('Template'));
        }

        Event::trigger('PublicTemplateLoaded', $rtn);

        return $rtn;
    }

    public static function getSitePath($type = 'View')
    {
        return APP_PATH . Config::getInstance()->get('site.namespace') . '/' . $type . '/';
    }

    public static function getSystemPath($type = 'View')
    {
        return CMS_PATH . '/' . $type . '/';
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
