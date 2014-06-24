<?php

namespace Octo\Invoicing\Admin\Controller;

use b8\Form;
use b8\Http\Response\JsonResponse;
use b8\Http\Response\RedirectResponse;
use Octo\Admin;
use Octo\Admin\Menu;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Template;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Event;
use Octo\Store;
use Octo\System\Model\Setting;

class InvoiceController extends Admin\Controller
{
    /**
     * @var \Octo\Invoicing\Service\InvoiceService
     */
    protected $invoiceService;

    protected $invoiceStore;

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
        $this->addBreadcrumb('Invoices', '/invoice');

        $invoiceStore = Store::get('Invoice');
        $adjustmentStore = Store::get('InvoiceAdjustment');
        $itemStore = Store::get('Item');
        $lineItemStore = Store::get('LineItem');

        $this->invoiceStore = $invoiceStore;
        $this->lineItemStore = $lineItemStore;
        $this->adjustmentStore = $adjustmentStore;
        $this->statusStore = Store::get('InvoiceStatus');

        $this->invoiceService = new InvoiceService($invoiceStore, $adjustmentStore, $itemStore, $lineItemStore);
    }

    public function index()
    {
        $this->setTitle('Manage Invoices');
        $this->view->invoices = $this->invoiceStore->getAll();
        $this->view->invoiceStatuses = $this->statusStore->getAll();
        $this->view->canPdf = SYSTEM_PDF_AVAILABLE;
    }

    public function add()
    {
        $this->setTitle('Add Invoice', 'Invoicing');
        $this->addBreadcrumb('Add Invoice', '/invoice/add');

        if ($this->request->getMethod() == 'POST') {

            $contact = Store::get('Contact')->getById($this->getParam('contact_id'));
            $invoiceDate = $this->getParam('created_date', null);

            if (!is_null($invoiceDate)) {
                $invoiceDate = new \DateTime($invoiceDate);
            } else {
                $invoiceDate = new \DateTime();
            }

            $dueDate = $this->getParam('due_date', null);

            if (!is_null($dueDate)) {
                $dueDate = new \DateTime($dueDate);
            } else {
                $dueDate = null;
            }

            $invoice = $this->invoiceService->createInvoice($this->getParam('title'), $contact, $invoiceDate, $dueDate);
            $this->invoiceService->updateInvoiceItems($invoice, $this->getParam('items', []));

            $this->response = new RedirectResponse();
            $redirect = '/'.$this->config->get('site.admin_uri').'/invoice/view/' . $invoice->getId();
            $this->response->setHeader('Location', $redirect);
            return;
        }
    }

    public function edit($key)
    {
        $item = $this->invoiceStore->getByPrimaryKey($key);
        $this->setTitle($item->getTitle(), 'Invoicing');

        if ($this->request->getMethod() == 'POST') {
            $title = $this->getParam('title', $item->getTitle());
            $contact = Store::get('Contact')->getById($this->getParam('contact_id', $item->getContactId()));
            $invoiceDate = $this->getParam('created_date', null);

            if (!is_null($invoiceDate)) {
                $invoiceDate = new \DateTime($invoiceDate);
            } else {
                $invoiceDate = $item->getCreatedDate();
            }

            $dueDate = $this->getParam('due_date', null);

            if (!is_null($dueDate) && empty($dueDate)) {
                $dueDate = null;
            } elseif (!is_null($dueDate) && !empty($dueDate)) {
                $dueDate = new \DateTime($dueDate);
            } else {
                $dueDate = $item->getDueDate();
            }

            $item = $this->invoiceService->updateInvoice($item, $title, $contact, $invoiceDate, $dueDate);

            $newStatusId = $this->getParam('invoice_status_id', null);
            if (!empty($newStatusId)) {
                $status = $this->statusStore->getById($newStatusId);
                $item = $this->invoiceService->updateInvoiceStatus($item, $status);
            }

            $this->invoiceService->updateInvoiceItems($item, $this->getParam('items', []));

            if ($this->request->isAjax()) {
                $this->response = new JsonResponse();
                $this->response->setContent($item);
                return $item;
            }

            header('Location: /' . $this->config->get('site.admin_uri') . '/invoice/view/' . $item->getId());
            die;
        }

        $this->view->item = $item;
        $this->view->invoiceItems = $this->lineItemStore->getByInvoiceId($key);
        $this->addBreadcrumb('Edit Invoice', '/invoice/edit/' . $key);
    }

    public function delete($key)
    {
        $item = $this->invoiceStore->getByPrimaryKey($key);

        if ($item) {
            $this->invoiceStore->delete($item);
        }

        $this->successMessage('Invoice deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/invoice');
    }

    public function view($key, $format = null)
    {
        $this->view->invoice = $this->invoiceStore->getByPrimaryKey($key);
        $this->view->invoiceItems = $this->lineItemStore->getByInvoiceId($key);
        $this->view->invoiceAdjustments = $this->adjustmentStore->getByInvoiceId($key);
        $this->setTitle($this->view->invoice->getTitle(), 'Invoicing');

        foreach ($this->view->invoiceItems as $item) {
            if ($item->getQuantity() != 1) {
                $this->view->hasQuantity = true;
                break;
            }
        }

        $this->view->siteCompanyName = Setting::get('invoicing', 'company_name');
        $this->view->siteCompanyAddress = nl2br(Setting::get('invoicing', 'company_address'));

        if (!is_null($format) && $format == 'pdf') {
            $this->response->disableLayout();
            $this->view->ownLayout = true;
            $this->view->siteUrl = $this->config->get('site.url');

            $htmlFile = '/tmp/invoice-'.$key.'.html';
            $pdfFile = '/tmp/invoice-'.$key.'.pdf';

            file_put_contents($htmlFile, $this->view->render());

            shell_exec('wkhtmltopdf -O Portrait -s A4 ' . $htmlFile . ' ' . $pdfFile);
            header('Content-Type: application/pdf');

            print file_get_contents($pdfFile);
            unlink($htmlFile);
            unlink($pdfFile);

            die();
        }
    }
}
