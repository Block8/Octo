<?php

namespace Octo\Admin;

use b8\Config;
use b8\View;
use Octo\Event;

class Template extends View\Template
{
    /**
     * Type of template to load
     *
     * @var string
     */
    protected static $templateType = 'admin_templates';

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

    /**
     * Load an admin template
     *
     * @param $template
     * @param null $module
     * @return null|Template
     *
     * @throws \Exception
     */
    public static function getAdminTemplate($template, $module = null)
    {
        $rtn = null;

        $path = static::getPath($template, $module);

        if (!is_null($path)) {
            $templateSrc = file_get_contents($path . $template . '.html');
            $rtn = new static($templateSrc);
            Event::trigger('AdminTemplateLoaded', $rtn);

            if (!is_null($rtn)) {
                $rtn->addFunction('hash', function ($args) {
                    return md5($args['value']);
                });
            }

            return $rtn;
        }

        throw new \Exception('Template does not exist: ' . $template);
    }

    public function includeTemplate($args, &$view)
    {
        if ($view instanceof static) {
            $template = static::getAdminTemplate($args['template']);
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
