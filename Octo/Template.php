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

    public function includeTemplate($args, &$view)
    {
        if ($view instanceof AdminTemplate) {
            $template = static::getAdminTemplate($args['template']);
        } else {
            $template = static::getPublicTemplate($args['template']);
        }

        unset($args['template']);

        foreach ($args as $key => $val) {
            $template->{$key} = $val;
        }

        return $template->render();
    }

    public function render()
    {
        $code = parent::render();
        Event::trigger('OnTemplateRender', $code);
        return $code;
    }

    public function getTemplateCode()
    {
        return $this->viewCode;
    }

    public function setTemplateCode($code)
    {
        $this->viewCode = $code;
    }
}
