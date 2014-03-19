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
     * @var array
     */
    protected $content;

    /**
     * @var array
     */
    protected $args = [];

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

        $directory = new \DirectoryIterator(CMS_PATH . 'Block/');

        foreach ($directory as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isFile() && $file->getExtension() == 'php') {
                $className = $file->getBasename('.php');
                $blocks[$className] = self::getBlockInformation('Octo', $className);
            }
        }

        return $blocks;
    }

    protected static function getSiteBlocks()
    {
        $blocks = array();
        $config = \b8\Config::getInstance();
        $blockPath = APP_PATH . $config->get('site.namespace') . '/Block/';

        if (!is_dir($blockPath)) {
            return $blocks;
        }

        $directory = new \DirectoryIterator($blockPath);

        foreach ($directory as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isFile() && $file->getExtension() == 'php') {
                $className = $file->getBasename('.php');
                $blocks[$className] = self::getBlockInformation($config->get('site.namespace'), $className);
            }
        }

        return $blocks;
    }

    protected static function getBlockInformation($namespace, $block)
    {
        $fullClass = $namespace . '\\Block\\' . $block;
        $rtn = array_merge(['namespace' => $namespace, 'class' => $block], $fullClass::getInfo());

        return $rtn;
    }

    public static function create($type, $content)
    {
        $config = \b8\Config::getInstance();

        $siteNs = '\\' . $config->get('site.namespace') . '\\Block\\' . $type;
        $systemNs = '\\' . $config->get('b8.app.namespace') . '\\Block\\' . $type;

        if (class_exists($siteNs)) {
            return new $siteNs($content);
        } elseif (class_exists($systemNs)) {
            return new $systemNs($content);
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

    public function render()
    {
        if (defined('USE_DEFERRED_RENDERING')) {
            return $this->renderDeferred();
        }

        $parts = explode('\\', get_class($this));
        $class = array_pop($parts);

        $this->view = Template::getPublicTemplate('Block/' . $class);

        $rtn = $this->renderNow();

        if ($rtn === false) {
            return '';
        } elseif (is_string($rtn)) {
            return $rtn;
        } else {
            return $this->view->render();
        }
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

    public function setArgs(array $args)
    {
        $this->args = $args;
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
