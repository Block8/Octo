<?php
namespace Octo\System\Admin\Controller;

use b8\Form;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu;

class SettingsController extends Controller
{
    /**
     * Setup page
     *
     * @return void
     */
    public function init()
    {
        $this->setTitle('Settings');
        $this->addBreadcrumb('Settings', '/settings');
    }

    public static function registerMenus(Menu $menu)
    {
        $menu->addRoot('Settings', '/settings')->setIcon('cog');
    }
}
