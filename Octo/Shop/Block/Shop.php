<?php

namespace Octo\Shop\Block;

use b8\Exception\HttpException\NotFoundException;
use Octo\Block;
use Octo\Store;
use Octo\Template;

class Shop extends Block
{
    /**
     * @var \Octo\Shop\Store\ItemStore
     */
    protected $itemStore;
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

    public function init() {
        $this->categoryStore = Store::get('Category');
        $this->productStore = Store::get('Item');
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
        $this->view = Template::getPublicTemplate('ShopCategoryList');
        $this->view->categories = $this->categoryStore->getAllForScope('shop', 'position ASC, name ASC', 'Item');
    }

    protected function renderProductList()
    {
        $categorySlug = $this->uriParts[0];
        $category = $this->categoryStore->getByScopeAndSlug('shop', $categorySlug);

        $this->view = Template::getPublicTemplate('ShopProductList');
        $this->view->category = $category;
        $this->view->products = $this->productStore->getByCategoryId($category->getId());
    }

    protected function renderProduct()
    {
        $categorySlug = $this->uriParts[0];
        $category = $this->categoryStore->getByScopeAndSlug('shop', $categorySlug);

        $productSlug = $this->uriParts[1];

        $this->view = Template::getPublicTemplate('ShopProduct');
        $this->view->category = $category;
        $product  = $this->productStore->getBySlug($productSlug);

        if (!$product) {
            throw new NotFoundException;
        } else {
            $this->view->product = $product;
        }
    }
}
