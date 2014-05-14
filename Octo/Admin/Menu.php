<?php
namespace Octo\Admin;

use b8\Config;
use Octo\Admin\Menu\Item;

class Menu
{
    /**
     * @var Item[]
     */
    protected $menu = [];

    /**
     * Constructor - setup menu and add dashboard
     */
    public function __construct()
    {
        $this->setupControllerMenus();
        $menu = $this->menu;
        $this->menu = [];
        sort($menu);
        $this->addRoot('Dashboard', '/', false)->setIcon('home');
        $this->menu = array_merge($this->menu, $menu);
    }

    /**
     * Render the menu as HTML
     *
     * @return string
     */
    public function __toString()
    {

        $rtn = '<ul>';

        foreach ($this->menu as $item) {
            $rtn .= $item;
        }

        $rtn .= '</ul>';

        return $rtn;
    }

    /**
     * @param $title
     * @param $link
     * @param bool $hidden
     * @return Item
     */
    public function addRoot($title, $link, $hidden = false)
    {
        $item = Item::create($title, $link, $hidden, true);

        $this->menu[$title] = $item;

        return $this->menu[$title];
    }

    /**
     * Get the root item for the requested title
     *
     * @param $title
     * @return Item
     */
    public function getRoot($title)
    {
        return $this->menu[$title];
    }

    /**
     * Setup the menus for each controller
     */
    protected function setupControllerMenus()
    {
        $this->config = Config::getInstance();

        $paths = Config::getInstance()->get('Octo.paths.namespaces');

        foreach ($paths as $namespace => $path) {
            $thisPath = $path . 'Admin/Controller/*.php';
            $files = glob($thisPath);

            foreach ($files as $file) {
                $this->getControllerMenuItems($file, $namespace);
            }
        }
    }

    /**
     * Get menus for each controller
     *
     * @param $file
     * @param $namespace
     */
    protected function getControllerMenuItems($file, $namespace)
    {
        $controller = '\\' . $namespace . '\\Admin\\Controller\\' . str_replace('.php', '', basename($file));

        if (method_exists($controller, 'registerMenus')) {
            $controller::registerMenus($this);
        }
    }

    /**
     * Get the menu array as a tree
     *
     * @return array
     */
    public function getTree()
    {
        $rtn = [];

        foreach ($this->menu as $item) {
            $thisItem = [];
            $thisItem['title'] = $item->getTitle();
            $thisItem['uri'] = $item->getLink(false);

            if ($item->hasChildren()) {
                $thisItem['children'] = $item->getTree();
            }

            $rtn[] = $thisItem;
        }

        return $rtn;
    }
}
