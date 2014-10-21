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
    const CATEGORY_ECARDS = 6;      // TODO: Remove magic category number

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
        $eCardHash = '';

        if (isset($itemData['variants'])) {
            foreach ($itemData['variants'] as $variantData) {
                $variant = $this->itemVariantStore->getById($variantData['variant']);
                $title = $variant->getVariant()->getTitle();
                $description .= ' [' . $title . ': ' . $variant->getVariantOption()->getOptionTitle() . '] ';
                $itemPrice += $variantData['adjustment'];
            }
        }
        if ($item->getCategoryId() == self::CATEGORY_ECARDS) {
            $params = $this->decodeMetaData($metadata);
            $eCardHash = md5($params['message']);
            $countEcardItemId = $this->lineStore->getCountItemsByItemIdFromBasket($basket->getId(), $itemId);
            if ($countEcardItemId > 0) {
                $description .= ' [Message #'.($countEcardItemId+1).']';
            }
        }

        //Check if line item already exist
        //if exist receive, sum, update
        $lineItemInBasket = $this->lineStore->getLineItemFromBasket($basket->getId(), $itemId, $description, $eCardHash);

        if(!is_null($lineItemInBasket) && count($lineItemInBasket)>0)
        {
            $lineItemInBasket = $lineItemInBasket[0];

            if ($lineItemInBasket->Item->getCategoryId() == self::CATEGORY_ECARDS) {
                $params = $this->decodeMetaData($lineItemInBasket->getMetaData());
                $emailAddresses = explode(";", $params['to_email']);

                $newParams = $this->decodeMetaData($metadata);
                $newEmailAddresses = explode(";", $newParams['to_email']);
                $newEmailAddresses = $this->combineAndFilterEmails($emailAddresses, $newEmailAddresses);

                $newParams['to_email'] = implode(';', $newEmailAddresses);
                $toMetaData[] = array('name'=> 'message', 'value'=>$newParams['message']);
                $toMetaData[] = array('name'=> 'to_email', 'value'=>$newParams['to_email']);
                $lineItemInBasket->setMetaData(json_encode($toMetaData));
                $quantity  = count($newEmailAddresses);
            } else {
                $quantity += $lineItemInBasket->getQuantity();
            }

            $linePrice = $itemPrice * $quantity;
            $lineItemInBasket->setQuantity($quantity);
            $lineItemInBasket->setItemPrice($itemPrice);
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
            if ($item->getCategoryId() == self::CATEGORY_ECARDS) {
                $lineItem->setEcardHash($eCardHash);
            }

            $lineItem = $this->lineStore->saveByInsert($lineItem);
        }

        if($lineItem) {
            $this->applyDiscountForItemInCategory($item, $basket);
        }

        return $lineItem;
    }

    public function applyDiscountForItemInCategory(Item $item, ShopBasket $basket)
    {
        $discountTable = $this->itemDiscountStore->getDiscountTableForCategory($item->getCategoryId());

        if($discountTable) {
            //there is a discount for Category, count all items for Category in Basket
            $existingCategoryQuantity = $this->lineStore->getCountItemsInCategory($basket->getId(), $item->getCategoryId());
            $newItemPrice = $this->calculateDiscountedPrice($item, $existingCategoryQuantity, $discountTable);

          //  if ($newItemPrice != $item->getPrice()) {

                /** @var \Octo\Invoicing\Model\LineItem[] $itemsInBasket */
                $itemsInBasket = $this->lineStore->getLineItemInCategoryFromBasket($basket->getId(), $item->getCategoryId());

                foreach ($itemsInBasket as $itemInBasket) {
                    $itemInBasket->setId($itemInBasket->getId()); //need it
                    $itemInBasket->setItemPrice($newItemPrice);
                    $newLinePrice = round($newItemPrice * $itemInBasket->getQuantity() ,2);
                    $itemInBasket->setLinePrice($newLinePrice);
                    $this->lineStore->saveByUpdate($itemInBasket);
                }

                return $newItemPrice;
           // }
        }

        return $item->getPrice();
    }

    /**
     * @param $metaData string
     * @return array
     */
    protected function decodeMetaData($metaData)
    {
        $json_array = json_decode($metaData, true);

        $params = array();
        for ($i = 0; $i < sizeof($json_array); $i++) {
            $key = $json_array[$i]['name'];
            $params[$key] = $json_array[$i]['value'];
        }

        return $params;
    }

    /**
     * @param $emailAddresses
     * @param $newEmailAddresses
     * @return array
     */
    protected function combineAndFilterEmails($emailAddresses, $newEmailAddresses)
    {
        $retEmailAddresses = array_merge($emailAddresses, $newEmailAddresses);
        $retEmailAddresses = array_filter($retEmailAddresses);
        $retEmailAddresses = array_unique($retEmailAddresses);

        return $retEmailAddresses;
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



}
