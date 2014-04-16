<?php

namespace Octo\Menu\Block;

use Octo\Template;
use Octo\Block;
use Octo\Menu\Store\MenuStore;
use Octo\Menu\Store\MenuItemStore;

class Menu extends Block
{
    /**
     * @var MenuStore
     */
    protected $menuStore;
    /**
     * @var MenuItemStore
     */
    protected $menuItemStore;

    public static function getInfo()
    {
        return ['title' => 'Menu', 'editor' => false];
    }

    public function renderNow()
    {
        $tag = $this->templateParams['id'];

        $this->menuStore = new MenuStore();
        $this->menuItemStore = new MenuItemStore();

        $menu = $this->menuStore->getByTemplateTag($tag);

        if ($menu) {
            $this->view->menu = $this->menuItemStore->getForMenu($menu->getId());
        }

        if (isset($this->view->menu) && is_array($this->view->menu)) {
            foreach ($this->view->menu as $item) {
                if ($this->page->getUri() == $item->getPage()->getUri()) {
                    $item->setCurrent('current');
                }
            }
        }
    }
}
