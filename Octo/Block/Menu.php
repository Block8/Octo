<?php

namespace Octo\Block;

use Octo\Template;
use Octo\Block;
use Octo\Store\MenuStore;
use Octo\Store\MenuItemStore;

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
    }
}
