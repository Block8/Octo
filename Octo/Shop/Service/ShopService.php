<?php

namespace Octo\Shop\Service;

use b8\Config;
use Katzgrau\KLogger\Logger;
use Octo\Invoicing\Model\Item;
use Octo\Store;
use Octo\System\Model\Contact;
use Octo\Invoicing\Model\LineItem;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Invoicing\Store\InvoiceStore;
use Octo\Invoicing\Store\ItemStore;
use Octo\Invoicing\Store\LineItemStore;
use Octo\Shop\Model\ShopBasket;
use Octo\Shop\Store\ItemVariantStore;
use Octo\Shop\Store\ShopBasketStore;
use Psr\Log\LogLevel;

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
    protected $itemVariantStore;
    /**
     * @var \Octo\Shop\Store\ItemDiscountStore
     */
    protected $itemDiscountStore;
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
        ItemVariantStore $itemVariantStore,
        ShopBasketStore $basketStore,
                        $itemDiscountStore
    ) {
        $this->itemStore = $itemStore;
        $this->lineStore = $lineStore;
        $this->itemVariantStore = $itemVariantStore;
        $this->basketStore = $basketStore;
        if (!is_null($itemDiscountStore)) {
            $this->itemDiscountStore = $itemDiscountStore;
        }
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
                $variant = $this->itemVariantStore->getById($variantData['variant']);
                $title = $variant->getVariant()->getTitle();
                $description .= ' [' . $title . ': ' . $variant->getVariantOption()->getOptionTitle() . '] ';
                $itemPrice += $variantData['adjustment'];
            }
        }

        $discountTable = $this->itemDiscountStore->getDiscountTableForCategory($item->getCategoryId());
        $itemPrice = $this->calculateDiscountedPrice($item, $quantity, $discountTable);

        //Check if line item already exist
        //if exist receive, sum, update
        $lineItemInBasket = $this->lineStore->getLineItemFromBasket($basket->getId(), $itemId ,$description);
        if(!is_null($lineItemInBasket) && count($lineItemInBasket)>0)
        {
            $lineItemInBasket = $lineItemInBasket[0];
            $lineItemInBasket->setQuantity($lineItemInBasket->getQuantity() + $quantity);
            $itemPrice = $this->calculateDiscountedPrice($item, $lineItemInBasket->getQuantity(), $discountTable);
            $linePrice = $itemPrice * $lineItemInBasket->getQuantity();
            $lineItemInBasket->setItemPrice($itemPrice);
            $lineItemInBasket->setLinePrice(round($linePrice, 2));
            $lineItem = $this->lineStore->saveByUpdate($lineItemInBasket);
        } else {
            //else Save By Insert (new item)

            $itemPrice = $this->calculateDiscountedPrice($item, $quantity, $discountTable);
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

    protected function calculateDiscountedPrice(Item $item, $quantity, $discountTable)
    {
        $discountedPrice = $item->getPrice();

        foreach ($discountTable as $minAmount => $productPrice) {
            if ($quantity >= $minAmount) {
                $discountedPrice = $item->getPrice() + $productPrice;
            }
        }

        return $discountedPrice;
    }



    /**
     * Create a new invoice or use generated for the same BasketId, ContactId and unpaid invoice
     * @param InvoiceService $service
     * @param ShopBasket $basket
     * @param Contact $contact
     * @return \Octo\Invoicing\Model\Invoice
     */
    public function createInvoiceForBasket(InvoiceService $service, ShopBasket $basket, Contact $contact)
    {
        $orderTitle = 'Order (Basket #' . $basket->getId() . ')';

        //Try to avoid creating new invoice for the same basket, same contact, and unpaid invoice
        $this->invoiceStore = Store::get('Invoice');
        $isInvoice = $this->invoiceStore->getInvoiceAlreadyCreated($orderTitle, $contact);

        $this->config = Config::getInstance();
        $log = new Logger($this->config->get('logging.directory'), LogLevel::DEBUG);
        $log->debug('Found invoice? Id=' . ($isInvoice ? $isInvoice->getId() : 'not found'));

        if ($isInvoice) {
            $invoice = $isInvoice;
        } else {
            $invoice = $service->createInvoice($orderTitle, $contact, new \DateTime(), null);
        }
        $this->lineStore->copyBasketToInvoice($basket, $invoice);
        $service->updateSubtotal($invoice);

        return $invoice;
    }

}
