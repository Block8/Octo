<?php

namespace Octo\Pages\Block;

use b8\Database;
use Octo\Block;
use Octo\Event;
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

    public function renderDeferred(&$data)
    {
        $this->view->items = $this->dataStore['breadcrumb'];
        $data['output'] = str_replace('{@@@octo.breadcrumb@@@}', $this->view->render(), $data['output']);
    }


    public function renderNow()
    {
        if (!isset($this->dataStore['breadcrumb']) || !count($this->dataStore['breadcrumb'])) {
            $this->dataStore['breadcrumb'] = $this->getAncestors();
        }

        return '{@@@octo.breadcrumb@@@}';
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
