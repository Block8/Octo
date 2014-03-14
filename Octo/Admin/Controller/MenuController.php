<?php
namespace Octo\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Admin\Controller;
use Octo\Admin\Menu as AdminMenu;
use Octo\Model\Menu;
use Octo\Store;

/**
 * Class MenuController
 */
class MenuController extends Controller
{
    /**
     * @var \Octo\Store\MenuStore
     */
    protected $menuStore;
    /**
     * @var \Octo\Store\MenuItemStore
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

        $this->setTitle('Menus');
        $this->addBreadcrumb('Menus', '/menu');
    }

    public function index()
    {
        $menus = $this->menuStore->getAll();
        $this->view->menus = $menus;
    }

    public function add()
    {
        $this->addBreadcrumb('Add Menu', '/menu/add');

        if ($this->request->getMethod() == 'POST') {
            $values = $this->getParams();
            $form = $this->menuForm($values, 'edit');

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
        $form = new Form();
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
}
