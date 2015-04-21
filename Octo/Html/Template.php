<?php

namespace Octo\Html;

use b8\Config;
use b8\Html\Template as HtmlTemplate;
use Octo\Event;

class Template extends HtmlTemplate
{
    public static function load($template, $module = null)
    {
        $html = self::loadPartial($template, $module);

        $tpl = new self($html, $template);
        Event::trigger('PublicTemplateLoaded', $tpl);

        return $tpl;

        throw new \Exception('Template does not exist: ' . $template);
    }

    public static function loadPartial($template, $module = null)
    {
        $path = self::getPath($template, $module);

        if (!is_null($path)) {
            $html = file_get_contents($path . $template . '.html');
            return static::processIncludes($html);
        }

        return '<!-- Octo: Missing Partial: ' . $template . ' -->';
    }

    public static function exists($template, $module = null)
    {
        if (!is_null(self::getPath($template, $module))) {
            return true;
        }

        return false;
    }

    public static function getPath($template, $module = null)
    {
        $config = Config::getInstance();
        $paths = array_reverse($config->get('Octo.paths.templates', []));

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

    public function render()
    {
        Event::trigger('HtmlTemplateRender', $this);
        return parent::render();
    }
}