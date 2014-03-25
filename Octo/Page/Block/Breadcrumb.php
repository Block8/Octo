<?php

namespace Octo\Page\Block;

use b8\Database;
use Octo\Block;
use Octo\Page\Model\Page;
use Octo\Store;
use Octo\Template;

class Breadcrumb extends Block
{
    /**
     * @var Page
     */
    protected $pageStore;

    public static function getInfo()
    {
        return ['title' => 'Breadcrumb'];
    }

    public function init()
    {
        $this->pageStore = Store::get('Page');
    }

    public function renderNow()
    {
        if (!isset($this->page)) {
            return;
        }

        $this->view->items = $this->getAncestors();
    }

    protected function getAncestors()
    {
        $rtn = [];
        $rtn[] = [
            'uri' => $this->page->getUri(),
            'title' => $this->page->getCurrentVersion()->getShortTitle(),
            'active' => true,
        ];

        $page = $this->page;

        while ($page->getParentId()) {
            $page = $page->getParent();

            $rtn[] = [
                'uri' => $page->getUri(),
                'title' => $page->getCurrentVersion()->getShortTitle(),
            ];
        }

        return array_reverse($rtn);
    }
}
