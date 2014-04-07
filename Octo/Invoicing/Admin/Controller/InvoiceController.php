<?php

namespace Octo\Invoicing\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Admin;
use Octo\Admin\Menu;
use Octo\Admin\Form as FormElement;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Event;
use Octo\Store;

class InvoiceController extends Admin\Controller
{
    /**
     * @var \Octo\Invoicing\Service\InvoiceService
     */
    protected $invoiceService;

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
        $this->addBreadcrumb('Invoices', '/item');

        $invoiceStore = Store::get('Invoice');
        $adjustmentStore = Store::get('InvoiceAdjustment');
        $itemStore = Store::get('Item');
        $lineItemStore = Store::get('LineItem');

        $this->invoiceService = new InvoiceService($invoiceStore, $adjustmentStore, $itemStore, $lineItemStore);
    }

    public function index()
    {
        $this->view->items = $this->store->getAll();
    }

    public function add()
    {
        $this->addBreadcrumb('Add Invoice', '/invoice/add');

        if ($this->request->getMethod() == 'POST') {

            $contact = Store::get('Contact')->getById($this->getParam('contact_id'));
            $invoiceDate = $this->getParam('created_date', null);

            if (!is_null($invoiceDate)) {
                $invoiceDate = new \DateTime($invoiceDate);
            } else {
                $invoiceDate = new \DateTime();
            }

            $invoice = $this->invoiceService->createInvoice($contact, $invoiceDate);
            $this->invoiceService->updateInvoiceItems($invoice, $this->getParam('items', []));

            var_dump($invoice); die;
        }
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
