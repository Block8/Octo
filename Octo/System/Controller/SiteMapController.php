<?php

namespace Octo\System\Controller;

use Octo\Controller;
use Octo\Pages\Model\Page;
use Octo\Store;
use Octo\Template;
use Octo\BlockManager;
use Octo\Event;
use Octo\Menu\Store\MenuItemStore;

class SiteMapController extends Controller
{
    /**
     * @var \Octo\Pages\Store\PageStore
     */
    protected $pageStore;
    /**
     * @var \Octo\Pages\Model\Page
     */
    protected $page;
    /**
     * @var \Octo\Pages\Model\PageVersion
     */
    protected $version;
    /**
     * @var MenuItemStore
     */
    protected $menuItemStore;


    public function init()
    {
        $this->pageStore     = Store::get('Page');
        $this->menuItemStore = new MenuItemStore();
    }


    protected function getChildrenPages()
    {
        $this->ancestors = $this->getAncestors();

        $this->allChildren = true;
        $start = count($this->ancestors);
        $maxDepth = 3;

        if (isset($this->ancestors[$start - 1])) {
            $items = $this->buildTree($this->ancestors[$start - 1], 0, $maxDepth);

            if (isset($items['children'])) {
                return $items['children'];
            } else {
                return [];
            }
        }
    }

    protected function getAncestors()
    {
        $rtn = [$this->page];
        $page = $this->page;

        while ($page->getParentId()) {
            $page = $page->getParent();
            $rtn[] = $page;
        }

        return array_reverse($rtn);
    }

    protected function getChildren(Page $page)
    {
        $options = [
            'order' => [
                ['position', 'ASC'],
            ]
        ];

        $children = $this->pageStore->getByParentId($page->getId(), $options);

        if (count($children)) {
            return $children;
        }

        return null;
    }

    protected function buildTree(Page $item, $depth, $maxDepth)
    {
        $isAncestor = $this->isAncestor($item);

        $rtn = [];
        $rtn['uri'] = $item->getUri();
        $rtn['title'] = $item->getCurrentVersion()->getShortTitle();
        $rtn['active'] = $isAncestor;

        if ($item->getId() == $this->page->getId()) {
            $rtn['current'] = true;
        }

        $children = $this->getChildren($item);

        if (!is_null($children)) {
            $rtn['children'] = [];

            foreach ($children as $child) {
                $rtn['children'][] = $this->buildTree($child, $depth + 1, $maxDepth);
            }
        }

        return $rtn;
    }

    protected function isAncestor(Page $page)
    {
        foreach ($this->ancestors as $ancestor) {
            if ($page->getId() == $ancestor->getId()) {
                return true;
            }
        }

        return false;
    }

    public function index()
    {
        $sitemap = array();
        $view = Template::getPublicTemplate('sitemap');

        $topMenu = $this->menuItemStore->getForMenu(1);

        $record['uri'] = '/';
        $record['title'] = 'Home';
        $record['active'] = false;

        $sitemap[] = $record;

        foreach ($topMenu as $menuItem)
        {
            $this->page = $this->pageStore->getById($menuItem->getPageId());
            $this->version = $this->page->getCurrentVersion();

            $record = array();

            $record['uri'] = $this->page->getUri();
            $record['title'] = $this->version->getShortTitle();

            $rtn = $this->getChildrenPages();

            if(count($rtn))
            {
                $record['active'] =  true;
                $record['children'] = $rtn;
            } else {
                $record['active'] =  false;
            }

            $sitemap[] = $record;
        }

        $view->items = $sitemap;

        $dataStore = [
            'breadcrumb' => [
                ['uri' => '/', 'title' => 'Home', 'active' => false],
                ['uri' => '/sitemap', 'title' => 'Sitemap', 'active' => true]
            ]
        ];

        $blockManager = new BlockManager();
        $blockManager->setDataStore($dataStore);
        $blockManager->setRequest($this->request);
        $blockManager->setResponse($this->response);
        $blockManager->attachToTemplate($view);

        $output = $view->render();

        $data = [
            'page' => null,
            'version' => null,
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
    }

}
