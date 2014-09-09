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

    //TODO: Will fix it later
    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $productStore;

    /**
     * @var \Octo\Shop\Store\ItemVariantStore
     */
    protected $itemVariantStore;
    /**
     * @var \Octo\Shop\Store\ItemRelatedStore
     */
    protected $itemRelatedStore;
    /**
     * @var bool Set URI extensions
     */
    protected $hasUriExtensions = true;
    /**
     * @var array Parts of the block URI passed
     */
    protected $uriParts = [];

    /**
     * @var \Octo\Categories\Store\CategoryStore
     */
    protected $categoryStore;


    public static function getInfo()
    {
        return ['title' => 'Shop', 'editor' => false, 'js' => []];
    }

    public function init()
    {
        $this->categoryStore = Store::get('Category');
        $this->productStore = Store::get('Item');
        $this->itemVariantStore = Store::get('ItemVariant');
        $this->itemRelatedStore = Store::get('ItemRelated');
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

        if (isset($this->dataStore['breadcrumb']) || is_array($this->dataStore['breadcrumb'])) {
            array_pop($this->dataStore['breadcrumb']);
            $this->dataStore['breadcrumb'][] = [
                'uri' => $this->page->uri . "/" . $categorySlug,
                'title' => $category->getName(),
                'active' => true,
            ];
        }

        $this->view = Template::getPublicTemplate('Block/ShopProductList');
        $this->view->page = $this->page;
        $this->view->category = $category;

        $products = [];
        if ($category->getActive()) {
            $products = $this->productStore->getByCategoryId($category->getId());
        }

        $this->view->products = $products;
    }

    protected function renderProduct()
    {
        $categorySlug = $this->uriParts[0];
        $category = $this->categoryStore->getByScopeAndSlug('shop', $categorySlug);

        $productSlug = $this->uriParts[1];
        $product = $this->productStore->getBySlugAndCategory($productSlug, $category->getId(), !(int)$this->request->getParam('preview', 0));

        if (!$product) {
            throw new NotFoundException;
        }

        if (isset($this->dataStore['breadcrumb']) || is_array($this->dataStore['breadcrumb'])) {
            array_pop($this->dataStore['breadcrumb']);
            $this->dataStore['breadcrumb'][] = [
                'uri' => $this->page->uri . "/" . $categorySlug,
                'title' => $category->getName(),
                'active' => true,
            ];
            $this->dataStore['breadcrumb'][] = [
                'uri' => $this->page->uri . "/" . $categorySlug . "/" . $productSlug,
                'title' => $product->getTitle(),
                'active' => true,
            ];
        }

        $this->view = Template::getPublicTemplate('Block/ShopProduct');
        $this->view->product = $product;
        $this->view->page = $this->page;
        $this->view->category = $category;
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

    /**
     * Get related products to display on webpage
     * @param $product
     * @return array
     */
    protected function setupRelatedProducts($product)
    {
        $itemRelated = $this->itemRelatedStore->getByItemID($product->getId());
        $products = [];

        if (count($itemRelated) > 0)
        {
            foreach ($itemRelated as $item) {
                $products[] = $item->getRelatedItem();
            }
        } else {
        //if there is no related products //as 01/09/2014 pick random
            $related = $this->productStore->getAll(true);

            shuffle($related);
            $products = array_slice($related, 0, 4);
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
