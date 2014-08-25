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

    public function __construct(
        ItemStore $itemStore,
        LineItemStore $lineStore,
        ItemVariantStore $variantStore,
        ShopBasketStore $basketStore
    ) {
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
        $itemId = $itemData['item_id'];
        $quantity = $itemData['quantity'];
        $metadata = $itemData['meta_data'];
        $item = $this->itemStore->getById($itemId);
        $description = $item->getTitle();
        $itemPrice = $item->getPrice();

        if (isset($itemData['variants'])) {
            foreach ($itemData['variants'] as $variantData) {
                $variant = $this->variantStore->getById($variantData['variant']);
                $title = $variant->getVariant()->getTitle();
                $description .= ' [' . $title . ': ' . $variant->getVariantOption()->getOptionTitle() . '] ';
                $itemPrice += $variantData['adjustment'];
            }
        }

        //Check if line item already exist
        //if exist receive, sum, update
        $lineItemInBasket = $this->lineStore->getLineItemFromBasket($basket->getId(), $itemId ,$description);
        if(!is_null($lineItemInBasket) && count($lineItemInBasket)>0)
        {
            $lineItemInBasket = $lineItemInBasket[0];
            $lineItemInBasket->setQuantity($lineItemInBasket->getQuantity() + $quantity);
            $linePrice = $itemPrice * $lineItemInBasket->getQuantity();
            $lineItemInBasket->setLinePrice(round($linePrice, 2));
            $lineItem = $this->lineStore->saveByUpdate($lineItemInBasket);
        } else {
            //else Save By Insert (new item)

            $linePrice = $itemPrice * $quantity;

            $lineItem = new LineItem();
            $lineItem->setItem($item);
            $lineItem->setItemPrice($itemPrice);
            $lineItem->setQuantity($quantity);
            $lineItem->setLinePrice(round($linePrice, 2));
            $lineItem->setBasket($basket);
            $lineItem->setDescription($description);
            $lineItem->setMetaData($metadata);

            $lineItem = $this->lineStore->saveByInsert($lineItem);
        }

        return $lineItem;
    }

    public function createInvoiceForBasket(InvoiceService $service, ShopBasket $basket, Contact $contact)
    {
        $orderTitle = 'Order (Basket #' . $basket->getId() . ')';
        $invoice = $service->createInvoice($orderTitle, $contact, new \DateTime(), null);
        $this->lineStore->copyBasketToInvoice($basket, $invoice);
        $service->updateSubtotal($invoice);

        return $invoice;
    }
}
