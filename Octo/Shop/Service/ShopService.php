<?php

namespace Octo\Shop\Service;

use Octo\System\Model\Contact;
use Octo\Invoicing\Model\LineItem;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Invoicing\Store\InvoiceStore;
use Octo\Invoicing\Store\ItemStore;
use Octo\Invoicing\Store\LineItemStore;
use Octo\Shop\Model\ShopBasket;
use Octo\Shop\Store\ItemVariantStore;
use Octo\Shop\Store\ShopBasketStore;

class ShopService
{
    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $itemStore;

    /**
     * @var \Octo\Invoicing\Store\LineItemStore
     */
    protected $lineStore;

    /**
     * @var \Octo\Shop\Store\ItemVariantStore
     */
    protected $variantStore;

    /**
     * @var \Octo\Shop\Store\ShopBasketStore
     */
    protected $basketStore;

    /**
     * @var \Octo\Invoicing\Store\InvoiceStore
     */
    protected $invoiceStore;

    public function __construct(ItemStore $itemStore, LineItemStore $lineStore, ItemVariantStore $variantStore, ShopBasketStore $basketStore)
    {
        $this->itemStore = $itemStore;
        $this->lineStore = $lineStore;
        $this->variantStore = $variantStore;
        $this->basketStore = $basketStore;
    }

    public function setInvoiceStore(InvoiceStore $store)
    {
        $this->invoiceStore = $store;
    }

    public function getBasket($basketId = null)
    {
        $basket = null;

        if (!is_null($basketId)) {
            $basket = $this->basketStore->getById($basketId);
        }

        if (is_null($basket)) {
            $basket = new ShopBasket();
            $basket->setCreatedDate(new \DateTime());
            $basket->setUpdatedDate(new \DateTime());

            $basket = $this->basketStore->saveByInsert($basket);
        }

        return $basket;
    }

    public function addItemToBasket(ShopBasket $basket, array $itemData)
    {
        $id = $itemData['item_id'];
        $quantity = $itemData['quantity'];

        $item = $this->itemStore->getById($id);
        $description = $item->getTitle();
        $itemPrice = $item->getPrice();

        foreach ($itemData['variants'] as $variantData) {
            $variant = $this->variantStore->getById($variantData['variant']);
            $description .= ' [' . $variant->getVariant()->getTitle() . ': ' . $variant->getVariantOption()->getOptionTitle() . '] ';
            $itemPrice += $variantData['adjustment'];
        }

        $linePrice = $itemPrice * $quantity;

        $lineItem = new LineItem();
        $lineItem->setItem($item);
        $lineItem->setItemPrice($itemPrice);
        $lineItem->setQuantity($quantity);
        $lineItem->setLinePrice(round($linePrice, 2));
        $lineItem->setBasket($basket);
        $lineItem->setDescription($description);

        $lineItem = $this->lineStore->saveByInsert($lineItem);

        return $lineItem;
    }

    public function createInvoiceForBasket(InvoiceService $service, ShopBasket $basket, Contact $contact)
    {
        $invoice = $service->createInvoice('Order (Basket #' . $basket->getId() . ')', $contact, new \DateTime(), null);
        $this->lineStore->copyBasketToInvoice($basket, $invoice);
        $service->updateSubtotal($invoice);

        return $invoice;
    }
}