<?php
namespace Octo\GatewayRSM2000\Admin\Controller;

use b8\Form;
use Octo\Admin;
use Octo\Admin\Menu;
use Octo\Event;
use Octo\Store;

class RsmController extends  Admin\Controller
{
    /**
     * @var \Octo\Invoicing\Store\InvoiceStore
     */
    protected $invoiceStore;

    /**
     * @var \Octo\GatewayRSM2000\Store\Rsm2000logStore
     */
    protected $rsm2000LogStore;

    public static function registerMenus(Menu $menu)
    {
        $invoices = $menu->addRoot('Logs', '#')->setIcon('eye');
        $invoices->addChild(new Menu\Item('RSM2000 logs', '/rsm'));
    }

    public function init()
    {
        $this->setTitle('Shop');
        $this->addBreadcrumb('Shop', '/shop');

        $this->invoiceStore = Store::get('Invoice');
        $this->rsm2000LogStore = Store::get('Rsm2000Log');
    }

    /**
     * Show dataTable with all records
     */
    public function index()
    {
        $this->setTitle('Rsm2000 logs');
        $this->view->rsm2000logs = $this->rsm2000LogStore->getAll();
    }
}
