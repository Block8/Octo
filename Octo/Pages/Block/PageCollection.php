<?php

namespace Octo\Pages\Block;

use b8\Database;
use Octo\Block;
use Octo\Pages\Model\Page;
use Octo\Store;
use Octo\Template;

class PageCollection extends Block
{
    /**
     * @var \Octo\Pages\Store\PageStore
     */
    protected $pageStore;

    public static function getInfo()
    {
        return ['title' => 'Page Collection', 'editor' => true, 'js' => ['/assets/backoffice/js/block/pagecollection.js']];
    }

    public function init()
    {
        $this->pageStore = Store::get('Page');
    }

    public function renderNow()
    {
        if (array_key_exists('template', $this->templateParams)) {
            $this->view = Template::getPublicTemplate('Block/Collection/' . $this->templateParams['template']);
        }

        $this->limit = 25;

        if (array_key_exists('limit', $this->templateParams)) {
            $this->limit = $this->templateParams['limit'] ? $this->templateParams['limit'] : $this->limit;
        }

        if (array_key_exists('pages', $this->content)) {
            $pages = [];
            $i = 0;
            foreach ($this->content['pages'] as $pageId) {
                if (++$i >= $this->limit) {
                    break;
                }

                $page = $this->pageStore->getById($pageId);

                if (!is_null($page)) {
                    $pages[] = $page;
                }
            }

            $this->view->pages = $pages;
        }
    }
}
