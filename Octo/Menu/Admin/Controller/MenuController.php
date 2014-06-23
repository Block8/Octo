<?php
namespace Octo\Menu\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu as AdminMenu;
use Octo\Menu\Model\Menu;
use Octo\Menu\Model\MenuItem;
use Octo\Store;

/**
 * Class MenuController
 */
class MenuController extends Controller
{
    /**
     * @var \Octo\Menu\Store\MenuStore
     */
    protected $menuStore;
    /**
     * @var \Octo\Menu\Store\MenuItemStore
     */
    protected $menuItemStore;

    public static function registerMenus(AdminMenu $menu)
    {
        $item = $menu->addRoot('Menus', '/menu')->setIcon('list-ul');
        $item->addChild(new AdminMenu\Item('Add Menu', '/menu/add'));
        $manage = new AdminMenu\Item('Manage Menus', '/menu');
        $manage->addChild(new AdminMenu\Item('Edit Menu', '/menu/edit', true));
        $manage->addChild(new AdminMenu\Item('Delete Menu', '/menu/delete', true));
        $item->addChild($manage);
    }

    /**
     * Setup initial menu
     *
     * @return void
     */
    public function init()
    {
        $this->menuStore = Store::get('Menu');
        $this->menuItemStore = Store::get('MenuItem');

        $this->setTitle('Menus');
        $this->addBreadcrumb('Menus', '/menu');
    }

    public function index()
    {
        $this->setTitle('Manage Menus');

        $menus = $this->menuStore->getAll();
        $this->view->menus = $menus;
    }

    public function add()
    {
        $this->setTitle('Add Menu', 'Menus');
        $this->addBreadcrumb('Add Menu', '/menu/add');

        if ($this->request->getMethod() == 'POST') {
            $values = $this->getParams();
            $form = $this->menuForm($values, 'add');

            if ($form->validate()) {
                $menu = new Menu();
                $menu->setValues($this->getParams());
                $menu = $this->menuStore->saveByInsert($menu);
                $this->successMessage($menu->getName() . ' was added successfully.', true);
                header('Location: /' . $this->config->get('site.admin_uri') . '/menu/edit/' . $menu->getId());
            }
        }

        $this->view->menuForm = $this->menuForm([], 'add');
    }

    public function edit($menuId)
    {
        $menu = $this->menuStore->getById($menuId);
        $this->setTitle($menu->getName(), 'Manage Menus');
        $this->addBreadcrumb($menu->getName(), '/menu/edit/' . $menu->getId());

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $menuId], $this->getParams());
            $form = $this->menuForm($values, 'edit');

            if ($form->validate()) {
                $menu->setValues($this->getParams());
                $menu = $this->menuStore->save($menu);
                $this->successMessage($menu->getName() . ' was edited successfully.', true);
                header('Location: /' . $this->config->get('site.admin_uri') . '/menu');
            }
        }

        $this->view->menu = $menu;
        $values = array_merge(['id' => $menuId], $menu->getDataArray());
        $this->view->menuForm = $this->menuForm($values, 'edit');
    }

    public function delete($menuId)
    {
        $menu = $this->menuStore->getById($menuId);
        $this->menuStore->delete($menu);
        $this->successMessage($menu->getName() . ' has been deleted.', true);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/' . $this->config->get('site.admin_uri') . '/menu');
    }

    protected function menuForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/menu/add');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/menu/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('name');
        $field->setRequired(true);
        $field->setLabel('Name');
        $fieldset->addField($field);

        $field = new Form\Element\Text('template_tag');
        $field->setRequired(true);
        $field->setLabel('Template Tag');
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Menu');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

    public function items($menuId)
    {
        if ($this->request->getMethod() == 'POST' && $this->request->isAjax()) {
            $this->updateMenuItems($menuId);
        }

        $menu = $this->menuStore->getById($menuId);
        $this->setTitle($menu->getName(), 'Manage Menus');
        $this->addBreadcrumb($menu->getName(), '/menu/items/' . $menu->getId());

        $items = $this->menuItemStore->getByMenuId($menuId, ['order' => [['position', 'ASC']]]);

        $this->view->menu = $menu;
        $this->view->items = $items;
    }

    protected function updateMenuItems($menuId)
    {
        // Are we updating menu positions?
        $items = $this->getParam('positions', null);

        if (!is_null($items)) {
            foreach ($items as $itemId => $position) {
                $item = $this->menuItemStore->getById($itemId);
                $item->setPosition($position);

                $this->menuItemStore->save($item);
            }
        }

        // Are we adding/editing an item?
        $data = $this->getParam('item', null);

        if (!is_null($data)) {
            if (!empty($data['id'])) {
                $item = $this->menuItemStore->getById($data['id']);
            } else {
                $item = new MenuItem();
                $item->setMenuId($menuId);
            }

            $item->setValues($data);

            $item = $this->menuItemStore->save($item);
            die(json_encode($item->toArray(2)));
        }

        // Are we deleting an item?
        $deleteId = $this->getParam('delete', null);

        if (!is_null($deleteId)) {
            $item = $this->menuItemStore->getById($deleteId);

            if (!is_null($item)) {
                $this->menuItemStore->delete($item);
            }
        }

        die('OK');
    }
}
