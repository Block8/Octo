<?php

namespace Octo\Controller;

use Exception;
use b8\Exception\HttpException\NotFoundException;
use b8\Exception\HttpException;
use Octo\Block;
use Octo\BlockManager;
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

        if (is_null($this->page) || !($this->page instanceof Page)) {
            throw new NotFoundException('Page does not exist: ' . $path);
        }

        $this->version = $this->page->getCurrentVersion();
        return $this->render();
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

        $blockManager = new BlockManager();
        $blockManager->setArgs($this->args);
        $blockManager->setContent($this->content);
        $blockManager->setPage($this->page);
        $blockManager->setPageVersion($this->version);
        $blockManager->setRequest($this->request);
        $blockManager->setResponse($this->response);
        $blockManager->attachToTemplate($template);

        return $template->render();
    }
}
