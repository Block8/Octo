<?php

namespace Octo\Shop\Block;

use b8\Exception\HttpException\NotFoundException;
use Octo\Block;
use Octo\Store;
use Octo\Template;
use Octo\Shop\Service\ShopService;

class Shop extends Block
{
    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $itemStore;
    /**
     * @var \Octo\Shop\Store\ItemVariantStore
     */
    protected $itemVariantStore;
    /**
     * @var \Octo\Shop\Store\RelatedItemStore
     */
    protected $relatedItemStore;
    /**
     * @var bool Set URI extensions
     */
    protected $hasUriExtensions = true;
    /**
     * @var array Parts of the block URI passed
     */
    protected $uriParts = [];

    public static function getInfo()
    {
        return ['title' => 'Product', 'editor' => false, 'js' => []];
    }

    public function init()
    {
        $this->categoryStore = Store::get('Category');
        $this->productStore = Store::get('Item');
        $this->itemVariantStore = Store::get('ItemVariant');
        $this->relatedItemStore = Store::get('RelatedItem');
    }

    public function renderNow()
    {
        if (is_null($this->uri)) {
            $this->uriParts = [];
            $uriPartsCount = 0;
        } else {
            $this->uriParts = explode('/', ltrim($this->uri, '/'));
            $uriPartsCount = count($this->uriParts);
        }

        if ($this->request->getMethod() == 'POST' && array_key_exists('add_item', $_POST)) {
            $this->addItemToBasket();
            die('OK');
        }

        // Will need explicit add to basket here
        switch ($uriPartsCount) {
            case 0:
                $this->renderCategoryList();
                break;
            case 1:
                $this->renderProductList();
                break;
            case 2:
                $this->renderProduct();
                break;
            default:
                $this->renderCategoryList();
        }
    }

    protected function renderCategoryList()
    {
        $this->view = Template::getPublicTemplate('Block/ShopCategoryList');
        $this->view->page = $this->page;
        $this->view->categories = $this->categoryStore->getAllForScope('shop', 'position ASC, name ASC', 'Item');
    }

    protected function renderProductList()
    {
        $categorySlug = $this->uriParts[0];
        $category = $this->categoryStore->getByScopeAndSlug('shop', $categorySlug);

        $this->view = Template::getPublicTemplate('Block/ShopProductList');
        $this->view->page = $this->page;
        $this->view->category = $category;
        $this->view->products = $this->productStore->getByCategoryId($category->getId());
    }

    protected function renderProduct()
    {
        $categorySlug = $this->uriParts[0];
        $category = $this->categoryStore->getByScopeAndSlug('shop', $categorySlug);

        $productSlug = $this->uriParts[1];

        $this->view = Template::getPublicTemplate('Block/ShopProduct');
        $this->view->page = $this->page;
        $this->view->category = $category;
        $product  = $this->productStore->getBySlug($productSlug);

        if (!$product) {
            throw new NotFoundException;
        } else {
            $this->view->product = $product;
        }

        $this->view->variants = $this->setupVariants($product);
        $this->view->related = $this->setupRelatedProducts($product);
    }

    protected function setupVariants($product)
    {
        $variants = [];

        foreach ($this->itemVariantStore->getAllForItem($product->getId()) as $itemVariant) {
            if (!isset($variants[$itemVariant->getVariantId()])) {
                $variantArray = array_merge($itemVariant->getVariant()->getDataArray(), ['options' => []]);
                $variants[$itemVariant->getVariantId()] = $variantArray;
            }

            $ivArray = $itemVariant->getDataArray();
            $optionsArray = $itemVariant->getVariantOption()->getDataArray();

            $computed = [
                'item_variant_id' => $ivArray['id'],
                'title' => $optionsArray['option_title'],
                'position' => $optionsArray['position'],
                'price_adjustment' => $ivArray['price_adjustment']
            ];

            $variants[$itemVariant->getVariantId()]['options'][] = $computed;
        }

        return $variants;
    }

    protected function setupRelatedProducts($product)
    {
        $relatedItems = $this->relatedItemStore->getByItemID($product->getId());
        $products = [];

        foreach ($relatedItems as $item) {
            $products[] = $item->getRelatedItem();
        }

        return $products;
    }

    protected function addItemToBasket()
    {
        $itemData = $this->request->getParam('add_item');

        $itemStore = Store::get('Item');
        $lineStore = Store::get('LineItem');
        $variantStore = Store::get('ItemVariant');
        $basketStore = Store::get('ShopBasket');

        $service = new ShopService($itemStore, $lineStore, $variantStore, $basketStore);
        $basket = $service->getBasket($itemData['basket_id']);

        $service->addItemToBasket($basket, $itemData);

        $items = $lineStore->getByBasketId($basket->getId());

        $itemCount = count($items);
        $basketTotal = 0;

        foreach ($items as $item) {
            $basketTotal += $item->getLinePrice();
        }

        die(json_encode(['id' => $basket->getId(), 'items' => $itemCount, 'total' => $basketTotal]));
    }
}
