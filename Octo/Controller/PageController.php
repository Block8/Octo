<?php

namespace Octo\Controller;

use Octo\Block;
use Octo\Controller;
use Octo\Model\Page;
use Octo\Model\PageVersion;
use Octo\Model\ContentItem;
use Octo\Store;
use Octo\Template;

class PageController extends Controller
{
    /**
     * @var \Octo\Store\PageStore
     */
    protected $pageStore;

    /**
     * @var \Octo\Store\PageVersionStore
     */
    protected $versionStore;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var \Octo\Model\Page
     */
    protected $page;

    /**
     * @var \Octo\Model\PageVersion
     */
    protected $version;

    /**
     * @var array
     */
    protected $args = [];

    public function init()
    {
        $this->pageStore = Store::get('Page');
        $this->versionStore = Store::get('PageVersion');
    }

    public function view()
    {
        $path = $this->request->getPath();

        if (strpos($path, '/_/') !== false) {
            $parts = explode('/_/', $path);
            $path = $parts[0];

            if ($path == '') {
                $path = '/';
            }

            $this->args = explode('/', $parts[1]);
        }

        $this->page = $this->pageStore->getByUri($path);

        if ($this->page) {
            header('HTTP/1.0 404 Not Found');
            error_log('Page could not be loaded: 404 thrown.');
            $this->version = $this->page->getCurrentVersion();
            return $this->render();
        }
    }

    public function preview($pageId)
    {
        $path = $this->request->getPath();

        if (strpos($path, '/_/') !== false) {
            $parts = explode('/_/', $path);
            $this->args = explode('/', $parts[1]);
        }

        $versionId = $this->getParam('version', null);
        $this->page = $this->pageStore->getById($pageId);

        if (is_null($versionId)) {
            $this->version = $this->pageStore->getLatestVersion($this->page);
        } else {
            $this->version = $this->versionStore->getById($versionId);
        }

        return $this->render();
    }

    public function render()
    {
        $this->content = json_decode($this->version->getContentItem()->getContent(), true);

        $template = Template::getPublicTemplate($this->version->getTemplate());
        $template->version = $this->version;
        $template->page = $this->page;
        $template->addFunction('block', [$this, 'renderBlock']);

        return $template->render();
    }

    public function renderBlock($args, &$view)
    {
        foreach ($args as &$value) {
            $value = $view->getVariable($value);
        }

        $type = $args['type'];
        $blockId = isset($args['id']) ? $args['id'] : null;

        try {
            $content = [];

            if (array_key_exists($blockId, $this->content)) {
                $content = $this->content[$blockId];
            }

            $block = Block::create($type, $content);
            $block->setRequest($this->request);
            $block->setResponse($this->response);
            $block->setArgs($this->args);
            $block->setTemplateParams($args);
            $block->setPage($this->page);
            $block->setPageVersion($this->version);

            $rtn = $block->render();
        } catch (\Exception $ex) {
            $rtn = '<!-- '.$ex->getMessage().' -->';
        }

        return $rtn;
    }
}
