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

    public static function exists($template, $path = null)
    {
        if (!is_null(static::getPath($template))) {
            return true;
        }

        return false;
    }

    public static function getPath($template)
    {
        $paths = [];

        if (strpos(get_called_class(), 'Admin') === false) {
            // Load a normal template
            $config = Config::getInstance();
            $moduleManager = $config->get('ModuleManager');
            $modules = $moduleManager->getEnabled()['Octo'];

            $paths = [];
            $systemPaths = [];

            $paths[] = $config->get('Octo.site_templates');
            foreach ($modules as $module) {
                // Load site-specific templates first, overwriting with blocks
                $paths[] = APP_PATH . $config->get('site.namespace') . '/' . $module . '/Template/';
                $paths[] = APP_PATH . $config->get('site.namespace') . '/' . $module . '/Template/Block/';
                // Then load CMS templates
                $systemPaths[] = CMS_PATH . $module . '/Template/';
                $systemPaths[] = CMS_PATH . $module . '/Template/Block/';
            }

            $paths = array_merge($paths, $systemPaths);
        }

        foreach ($paths as $path) {
            if (file_exists($path . $template . '.html')) {
                return $path;
            }
        }

        return null;
    }

    public static function getSitePath($template)
    {
        // TODO: Why are we passing in 'Template' here?
        $config = Config::getInstance();
        return $config->get('Octo.site_templates');
    }

    public static function getAdminTemplate($template, $module = null)
    {
        $rtn = null;
        $path = null;

        $paths = [];
        $config = Config::getInstance();

        // Are we including a system module template?
        if (strpos($template, '/') === false) {
            $paths[] = CMS_PATH . 'System/Admin/Template/';
            $paths[] = APP_PATH . $config->get('site.namespace') . '/System/Admin/Template/';
        } else {
            if (is_null($module)) {
                $parts = explode('/', $template);
                $module = $parts[0];
                array_shift($parts);
                $template = implode('/', $parts);
            }

            $paths[] = APP_PATH . $config->get('site.namespace') . '/System/Admin/Template/' . $module . '/';
            $paths[] = APP_PATH . $config->get('site.namespace') . '/' . $module . '/Admin/Template/';
            $paths[] = APP_PATH . $config->get('site.namespace') . '/' . $module . '/Admin/Template/' . $module . '/';
            $paths[] = CMS_PATH . 'System/Admin/Template/' . $module . '/';
            $paths[] = CMS_PATH . $module . '/Admin/Template/' . $module . '/';
            $paths[] = CMS_PATH . $module . '/Admin/Template/';
        }

        foreach ($paths as $path) {
            $file = $path . $template . '.html';
            if (file_exists($file)) {
                $rtn = AdminTemplate::createFromFile($file, null);
                Event::trigger('AdminTemplateLoaded', $rtn);
                return $rtn;
            }
        }

        throw new \Exception('Template does not exist: ' . $template);
    }

    public static function getPublicTemplate($template)
    {
        $rtn = null;

        if (PublicTemplate::exists($template)) {
            $rtn = PublicTemplate::createFromFile($template, PublicTemplate::getPath($template));
            Event::trigger('PublicTemplateLoaded', $rtn);
            return $rtn;
        }

        $config = Config::getInstance();
        $moduleManager = $config->get('ModuleManager');
        $modules = $moduleManager->getEnabled()['Octo'];

        foreach (glob(CMS_PATH . '*/Template') as $directory) {
            $module = basename(dirname($directory));

            if (!in_array($module, $modules)) {
                continue;
            }

            $directoryIterator = new \DirectoryIterator($directory);

            foreach ($directoryIterator as $file) {
                if ($file->isDot()) {
                    continue;
                }

                if ($file->isFile() && $file->getExtension() == 'html') {
                    $className = $file->getBasename('.php');
                    $namespace = "\\Octo\\$module\\Block";

                    $blocks[$className] = self::getBlockInformation($namespace, $className);
                }
            }
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
