<?php

namespace Octo\Invoicing\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Admin;
use Octo\Admin\Menu;
use Octo\Admin\Form as FormElement;
use Octo\Invoicing\Model\Invoice;
use Octo\Event;
use Octo\Store;

class InvoiceController extends Admin\Controller
{
    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $store;

    /**
     * Set up menus for this controller.
     * @param \Octo\Admin\Menu $menu
     */
    public static function registerMenus(Menu $menu)
    {
        $thisMenu = $menu->addRoot('Invoices', '/invoice')->setIcon('money');
        $thisMenu->addChild(new Menu\Item('Add Invoice', '/invoice/add'));

        $manage = new Menu\Item('Manage Invoices', '/invoice');
        $manage->addChild(new Menu\Item('Edit Invoice', '/invoice/edit', true));
        $manage->addChild(new Menu\Item('Delete Invoice', '/invoice/delete', true));
        $thisMenu->addChild($manage);

        $thisMenu->addChild(new Menu\Item('Reports', '/invoice/reports'));
    }

    /**
     * Set up the controller
     */
    public function init()
    {
        $this->store = Store::get('Invoice');
        $this->addBreadcrumb('Invoices', '/item');
    }

    public function index()
    {
        $this->view->items = $this->store->getAll();
    }

    public function add()
    {
        $this->addBreadcrumb('Add Invoice', '/invoice/add');
        $invoice = new Invoice();
        $invoice->setCreatedDate(new \DateTime());
        $this->view->item = $invoice;
    }

    public function edit($key)
    {
        $item = $this->store->getByPrimaryKey($key);
        $this->view->item = $item;
        $this->addBreadcrumb('Edit Invoice', '/invoice/edit/' . $key);

    }

    public function delete($key)
    {
        $item = $this->store->getByPrimaryKey($key);

        if ($item) {
            $this->store->delete($item);
        }

        $this->successMessage('Invoice deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/invoice');
    }

}
