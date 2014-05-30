<?php

namespace Octo;

use b8\Http\Response;
use b8\Http\Request;
use Octo\Pages\Model\Page;
use Octo\Pages\Model\PageVersion;
use Octo\Template;

abstract class Block
{
    /**
     * @var bool
     */
    protected $hasUriExtensions = false;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $templateParams = [];

    /**
     * @var \b8\Http\Request
     */
    protected $request;

    /**
     * @var \b8\Http\Response
     */
    protected $response;

    /**
     * @var Template
     */
    protected $view;

    /**
     * @var \Octo\Pages\Model\Page
     */
    protected $page;

    /**
     * @var \Octo\Pages\Model\PageVersion
     */
    protected $pageVersion;


    public static function getBlocks()
    {
        return array_merge(self::getBuiltInBlocks(), self::getSiteBlocks());
    }

    protected static function getBuiltInBlocks()
    {
        $blocks = array();

        $config = \b8\Config::getInstance();
        $moduleManager = $config->get('ModuleManager');
        $modules = $moduleManager->getEnabled()['Octo'];

        foreach (glob(CMS_PATH . '*/Block') as $directory) {
            $module = basename(dirname($directory));

            if (!in_array($module, $modules)) {
                continue;
            }

            $directoryIterator = new \DirectoryIterator($directory);

            foreach ($directoryIterator as $file) {
                if ($file->isDot()) {
                    continue;
                }

                if ($file->isFile() && $file->getExtension() == 'php') {
                    $className = $file->getBasename('.php');
                    $namespace = "\\Octo\\$module\\Block";

                    $blocks[$className] = self::getBlockInformation($namespace, $className);
                }
            }
        }

        return $blocks;
    }

    protected static function getSiteBlocks()
    {
        $blocks = array();

        $config = \b8\Config::getInstance();
        $moduleManager = $config->get('ModuleManager');
        $modules = $moduleManager->getEnabled()[$config->get('site.namespace')];

        foreach (glob(APP_PATH . $config->get('site.namespace'). '/*/Block') as $directory) {
            $module = basename(dirname($directory));

            if (!in_array($module, $modules)) {
                continue;
            }

            $directoryIterator = new \DirectoryIterator($directory);

            foreach ($directoryIterator as $file) {
                if ($file->isDot()) {
                    continue;
                }

                if ($file->isFile() && $file->getExtension() == 'php') {
                    $className = $file->getBasename('.php');
                    $namespace = "\\".$config->get('site.namespace')."\\$module\\Block";

                    $blocks[$className] = self::getBlockInformation($namespace, $className);
                }
            }
        }

        return $blocks;
    }

    protected static function getBlockInformation($namespace, $block)
    {
        $fullClass = $namespace . "\\" . $block;
        $rtn = array_merge(['namespace' => $namespace, 'class' => $block], $fullClass::getInfo());
        return $rtn;
    }

    /**
     * @param $type
     * @param $content
     * @return Block
     * @throws \Exception
     */
    public static function create($type, $content)
    {
        $config = \b8\Config::getInstance();
        $namespace = $config->get('Octo.namespaces.blocks.' . $type);
        $class = '\\' . $namespace . '\\Block\\' . $type;

        if (class_exists($class)) {
            return new $class($content);
        }

        throw new \Exception('Block type ' . $type . ' does not exist');
    }

    public function __construct($content)
    {
        $this->content = $content;
        $this->init();
    }

    protected function init()
    {

    }

    public function hasUriExtensions()
    {
        return $this->hasUriExtensions;
    }

    public function render()
    {
        if (defined('USE_DEFERRED_RENDERING')) {
            return $this->renderDeferred();
        }

        $parts = explode('\\', get_class($this));
        $class = array_pop($parts);

        if (isset($this->templateParams['template'])) {
            try {
                $this->view = Template::getPublicTemplate('Block/' . $this->templateParams['template']);
            } catch (\Exception $e) {
                // TODO: Something with this
                throw $e;
            }
        } else {
            $this->view = Template::getPublicTemplate('Block/' . $class);
        }

        foreach ($this->templateParams['variables'] as $key => $value) {
            $this->view->{$key} = $value;
        }

        $rtn = $this->renderNow();

        if ($rtn === false) {
            return '';
        }

        if (!is_string($rtn)) {
            $rtn = $this->view->render();
        }

        if (!empty($rtn) && isset($this->templateParams['wrapper'])) {
            $wrapper = Template::getPublicTemplate('Block/' . $this->templateParams['wrapper']);
            $wrapper->content = $rtn;
            $rtn = $wrapper->render();
        }

        return $rtn;
    }

    abstract public function renderNow();

    public function renderDeferred()
    {
        throw new \Exception('Deferred rendering is not yet supported.');
    }

    public function setRequest(Request &$request)
    {
        $this->request =& $request;
    }

    public function setResponse(Response &$response)
    {
        $this->response =& $response;
    }

    public function setUriExtension($uri)
    {
        $this->uri = $uri;
    }

    public function setTemplateParams(array $args)
    {
        $this->templateParams = $args;
    }

    public function setPage(Page &$page)
    {
        $this->page =& $page;
    }

    public function setPageVersion(PageVersion &$pageVersion)
    {
        $this->pageVersion =& $pageVersion;
    }
}
