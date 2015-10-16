<?php

namespace Octo;

use b8\Config;
use Octo\Event;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Template
{
    /**
     * @var bool
     */
    protected static $initialised = false;

    /**
     * @var \Twig_Loader_Filesystem
     */
    protected static $loader;

    /**
     * @var \Twig_Environment
     */
    protected static $twig;

    /**
     * @var string|bool
     */
    protected static $cache;

    /**
     * @var \Twig_TemplateInterface
     */
    protected $template;

    /**
     * @var string
     */
    protected $templateName;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * Sets up the twig template loader.
     */
    public static function init()
    {
        self::$loader = new Twig_Loader_Filesystem();

        $config = Config::getInstance();

        foreach (array_reverse($config->get('Octo.paths.templates', [])) as $path) {
            self::$loader->addPath($path);
        }

        foreach (array_reverse($config->get('Octo.paths.admin_templates', [])) as $path) {
            self::$loader->addPath($path, 'admin');
        }

        if (OCTO_CACHE_ENABLED) {
            self::$cache = OCTO_CACHE_PATH;
        } else {
            self::$cache = false;
        }

        self::$twig = new Twig_Environment(self::$loader, [
            'charset' => 'UTF-8',
            'cache' => self::$cache,
            'auto_reload' => true,
            'strict_variables' => false,
        ]);

        $functions = [];
        Event::trigger('TemplateInit', $functions);

        foreach ($functions as $name => $callable) {
            self::addFunction($name, $callable);
        }

        self::$initialised = true;
    }

    public static function addFunction($functionName, callable $function)
    {
        self::$twig->addFunction(new \Twig_SimpleFunction($functionName, $function));
    }

    /**
     * Load a template.
     * @param string $template
     * @param string $namespace
     */
    public function __construct($template, $namespace = null)
    {
        if (!self::$initialised) {
            self::init();
        }

        $template = $template . '.twig';

        if (!is_null($namespace)) {
            $template = '@' . $namespace . '/' . $template;
        }

        $this->templateName = $template;
        $this->template = self::$twig->loadTemplate($template);

        Event::trigger('PublicTemplateLoaded', $this);
    }

    public function render()
    {
        $output = $this->template->render($this->variables);
        Event::trigger('OnTemplateRender', $output);

        return $output;
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->variables)) {
            return $this->variables[$key];
        }

        return null;
    }

    public function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function getContext()
    {
        return $this->variables;
    }

    public function setContext($context)
    {
        $this->variables = $context;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __toString()
    {
        return $this->render();
    }
}
