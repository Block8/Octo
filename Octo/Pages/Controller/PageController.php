<?php

namespace Octo\Pages\Controller;

use Exception;
use b8\Exception\HttpException\NotFoundException;
use b8\Exception\HttpException;
use Octo\Block;
use Octo\BlockManager;
use Octo\Controller;
use Octo\Event;
use Octo\Pages\Model\Page;
use Octo\Pages\Model\PageVersion;
use Octo\System\Model\ContentItem;
use Octo\Store;
use Octo\Template;

class PageController extends Controller
{
    /**
     * @var \Octo\Pages\Store\PageStore
     */
    protected $pageStore;

    /**
     * @var \Octo\Pages\Store\PageVersionStore
     */
    protected $versionStore;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var \Octo\Pages\Model\Page
     */
    protected $page;

    /**
     * @var \Octo\Pages\Model\PageVersion
     */
    protected $version;

    /**
     * @var string
     */
    protected $uriExtension;

    protected $blockManager;

    public $breadcrumb = [];

    public function init()
    {
        $this->pageStore = Store::get('Page');
        $this->versionStore = Store::get('PageVersion');
    }

    public function view()
    {
        $path = $this->request->getPath();

        $this->page = $this->pageStore->getUriBestMatch($path);

        if (empty($this->page)) {
            throw new HttpException\NotFoundException('No page found.');
        }

        $this->uriExtension = substr($path, strlen($this->page->getUri()));

        if (empty($this->uriExtension)) {
            $this->uriExtension = null;
        }

        if (is_null($this->page) || !($this->page instanceof Page)) {
            throw new NotFoundException('Page does not exist: ' . $path);
        }

        $this->version = $this->page->getCurrentVersion();
        $template = $this->getTemplate();
        $blockManager = $this->getBlockManager($template);

        try {
            $output = $template->render();
        } catch (NotFoundException $e) {
            throw new NotFoundException('Page not found: ' . $path);
        } catch (Exception $e) {
            throw $e;
        }

        if (!is_null($this->uriExtension) && !$blockManager->uriExtensionsHandled()) {
            throw new NotFoundException('Page not found: ' . $path);
        }

        $data = [
            'page' => $this->page,
            'version' => $this->version,
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        if (Template::exists('include/meta')) {
            $template = Template::getPublicTemplate('include/meta');
            $template->page = $this->page;
            $template->version = $this->version;
            $template->datastore = $blockManager->getDataStore();

            $output = str_replace('{!@octo.meta}', $template->render(), $output);
        }

        return $output;
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

        $template = $this->getTemplate();
        $this->getBlockManager($template);
        return $template->render();
    }

    public function getTemplate()
    {
        $this->content = json_decode($this->version->getContentItem()->getContent(), true);

        $template = Template::getPublicTemplate($this->version->getTemplate());
        $template->version = $this->version;
        $template->page = $this->page;

        return $template;
    }

    public function getBlockManager(&$template)
    {
        $blockManager = new BlockManager();
        $blockManager->setUriExtension($this->uriExtension);
        $blockManager->setContent($this->content);
        $blockManager->setPage($this->page);
        $blockManager->setPageVersion($this->version);
        $blockManager->setRequest($this->request);
        $blockManager->setResponse($this->response);
        $blockManager->attachToTemplate($template);

        $this->blockManager = $blockManager;

        return $this->blockManager;
    }
}
