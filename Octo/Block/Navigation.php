<?php

namespace Octo\Block;

use Octo\Block;
use Octo\Store;
use Octo\Template;

class Navigation extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Navigation'];
    }

    public function renderNow()
    {
        $ancestors = $this->getAncestors();

        $this->view->items = [[
                'uri' => '/',
                'title' => 'Homepage',
                'children' => [
                    [
                        'uri' => '/about-us',
                        'title' => 'About us'
                    ],
                    [
                        'uri' => '/contact-us',
                        'title' => 'Contact us'
                    ],
                    [
                        'uri' => '/dance-with-us',
                        'title' => 'Dance with us'
                    ],
                ]
            ],
            [
                'uri' => '/nothing',
                'title' => 'Nothing'
            ],
        ];
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
}
