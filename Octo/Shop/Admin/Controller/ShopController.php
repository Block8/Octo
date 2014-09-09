<?php
namespace Octo\Shop\Admin\Controller;

use b8\Form;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Form\Element\ImageUpload;
use Octo\Invoicing\Model\Item;
use Octo\Shop\Model\ItemFile;
use Octo\Shop\Model\ItemVariant;
use Octo\System\Model\File;
use Octo\Store;
use Octo\Utilities\StringUtilities;

class ShopController extends Controller
{

    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $productStore;
    /**
     * @var \Octo\Categories\Store\CategoryStore
     */
    protected $categoryStore;
    /**
     * @var \Octo\Shop\Store\ItemFileStore
     */
    protected $itemFileStore;
    /**
     * @var \Octo\Shop\Store\VariantStore
     */
    protected $variantStore;
    /**
     * @var \Octo\Shop\Store\VariantOptionStore
     */
    protected $variantOptionStore;
    /**
     * @var \Octo\Shop\Store\ItemVariantStore
     */
    protected $itemVariantStore;
    /**
     * @var \Octo\FulfilmentHouse\Store\FulfilmentHouseStore
     */
    protected $supplierStore;


    /**
     * Return the menu nodes required for this controller
     *
     * @param Menu $menu
     * @return void
     */
    public static function registerMenus(Menu $menu)
    {
        $products = $menu->addRoot('Shop', '#')->setIcon('shopping-cart');
        $add = new Menu\Item('Add Product', '/shop/add-product');
        $products->addChild($add);
        $manage = new Menu\Item('Manage Shop', '/shop');
        $manage->addChild(new Menu\Item('View Products', '/shop/category', true));
        $manage->addChild(new Menu\Item('Edit Product', '/shop/edit-product', true));
        $manage->addChild(new Menu\Item('Delete Product', '/shop/delete-product', true));
        $manage->addChild(new Menu\Item('Activate Product', '/shop/activate-product', true));
        $manage->addChild(new Menu\Item('Deactivate Product', '/shop/deactivate-product', true));
        $manage->addChild(new Menu\Item('Variants Product', '/shop/product-variants', true));
        $manage->addChild(new Menu\Item('Manage Product Images', '/media/manage/shop', true));
        $manage->addChild(new Menu\Item('Add Product Image', '/media/add/shop', true));
        $manage->addChild(new Menu\Item('Edit Product Image', '/media/edit/shop', true));
        $manage->addChild(new Menu\Item('Delete Product Image', '/media/delete/shop', true));
        $products->addChild($manage);

        $variants = new Menu\Item('Manage Variants', '/variant');
        $variants->addChild(new Menu\Item('Edit Variant', '/variant/edit', true));
        $variants->addChild(new Menu\Item('Manage Variant Options', '/variant-option/manage', true));
        $variants->addChild(new Menu\Item('Add Variant Option', '/variant-option/add', true));
        $variants->addChild(new Menu\Item('Edit Variant Option', '/variant-option/edit', true));
        $variants->addChild(new Menu\Item('Delete Variant Option', '/variant-option/delete', true));
        $products->addChild($variants);
    }

    public function init()
    {
        $this->setTitle('Shop');
        $this->addBreadcrumb('Shop', '/shop');

        $this->productStore = Store::get('Item');
        $this->categoryStore = Store::get('Category');
        $this->itemFileStore = Store::get('ItemFile');
        $this->variantStore = Store::get('Variant');
        $this->variantOptionStore = Store::get('VariantOption');
        $this->itemVariantStore = Store::get('ItemVariant');

        $mm = $this->config->get('ModuleManager');
        if($mm->isEnabled('FulfilmentHouse')) {
            $this->supplierStore = Store::get('FulfilmentHouse');
        }

    }

    public function index()
    {
        $this->setTitle('Manage Shop');
        $this->view->categories = $this->categoryStore->getAllForScope('shop', 'active DESC, name ASC');
    }

    public function category($categoryId)
    {
        $cat = $this->categoryStore->getById($categoryId);
        $this->setTitle($cat->getName(), 'Manage Products');
        $this->addBreadcrumb($cat->getName(), '/shop/category/' . $categoryId);

        $this->view->products = $this->productStore->getByCategoryId($categoryId, false);
        $this->view->categories = $this->categoryStore->getAllForParent($categoryId);
    }


    /**
     * Check if title is unique for product in category
     * @param $title
     * @param $category
     * @param null $itemId
     * @return bool
     */
    protected function isUniqueSlugInCategory($title, $category, $itemId = null)
    {
        $slug = StringUtilities::generateSlug($title);

        $isUnique = $this->productStore->getBySlugAndCategory($slug, $category, false);

        if(!empty($isUnique) && !empty($itemId)) {
            return $isUnique->getId() == $itemId;
        }

        return empty($isUnique);
    }

    public function addProduct()
    {
        $this->setTitle('Add Product');
        $this->addBreadcrumb('Add Product', '/shop/add-product');
        $this->view->form = $this->productForm();

        if ($this->request->getMethod() == 'POST') {
            $form = $this->productForm($this->getParams());

            $isUniqueTitle = $this->isUniqueSlugInCategory($this->getParam('title'), $this->getParam('category_id'));

            if(!$isUniqueTitle) {
                $this->errorMessage('There is already a product with the same title in this category. Please try again.');
                $this->view->form = $form->render();
                return;
            }
            if ($form->validate()) {
                try {
                    $product = new Item();
                    $params = $this->getParams();

                    preg_match('/[0-9]+(\.[0-9][0-9])?$/', $params['price'], $priceMatches);
                    if (isset($priceMatches[0])) {
                        $params['price'] = $priceMatches[0];
                    } else {
                        $params['price'] = 0;
                    }

                    $product->setValues($params);
                    $product->setSlug(StringUtilities::generateSlug($product->getTitle()));
                    $product->setCreatedDate(new \DateTime());
                    $product->setUpdatedDate(new \DateTime());
                    $product->setActive(0);

                    $product = $this->productStore->save($product);

                    if ($files = File::upload('images', 'shop')) {
                        $this->itemFileStore = Store::get('ItemFile');

                        foreach ($files as $file) {
                            $itemFile = new ItemFile();
                            $itemFile->setItemId($product->getId());
                            $itemFile->setFileId($file->getId());
                            $this->itemFileStore->save($itemFile);
                        }
                    }

                    $this->successMessage($product->getTitle() . ' was added successfully.', true);
                    header('Location: ' . $this->config->get('site.url') . '/' . $this->config->get('site.admin_uri') . '/shop/category/' . $product->getCategoryId());
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the product. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error adding the product. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->productForm();
            $this->view->form = $form->render();
        }
    }

    public function editProduct($productId)
    {
        $product = $this->productStore->getById($productId);
        $this->view->product = $product;

        $this->setTitle($product->getTitle(), 'Products');
        $this->addBreadcrumb($product->getTitle(), '/shop/edit-product/' . $product->getId());

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $productId], $this->getParams());
            $form = $this->productForm($values, 'edit');

            $isUniqueTitle = $this->isUniqueSlugInCategory($values['title'], $values['category_id'], $values['id']);
            if(!$isUniqueTitle) {
                $this->errorMessage('There is already a product with the same title in this category. Please try again.');
                $this->view->form = $form->render();
                return;
            }
            if ($form->validate()) {
                try {
                    $params = $this->getParams();

                    preg_match('/[0-9]+(\.[0-9][0-9])?$/', $params['price'], $priceMatches);
                    if (isset($priceMatches[0])) {
                        $params['price'] = $priceMatches[0];
                    } else {
                        $params['price'] = 0;
                    }

                    if ($files = File::upload('images', 'shop')) {
                        $this->itemFileStore = Store::get('ItemFile');

                        foreach ($files as $file) {
                            $itemFile = new ItemFile();
                            $itemFile->setItemId($product->getId());
                            $itemFile->setFileId($file->getId());
                            $this->itemFileStore->save($itemFile);
                        }
                    }

                    $product->setValues($params);
                    $product->setSlug(StringUtilities::generateSlug($product->getTitle()));
                    $product->setUpdatedDate(new \DateTime());
                    $this->productStore->save($product);

                    $this->successMessage($product->getTitle() . ' was edited successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/shop/category/' . $product->getCategoryId());
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the product. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error editing the product. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $values = array_merge(['id' => $productId], $product->getDataArray());
            $this->view->form = $this->productForm($values, 'edit')->render();
        }
    }

    public function deleteProduct($productId)
    {
        $product = $this->productStore->getById($productId);

        try {
            $this->productStore->delete($product);
            $this->successMessage($product->getTitle() . ' was deleted successfully.', true);
        } catch (\Exception $ex) {
            $product->setActive(0);
            $this->productStore->save($product);
            $this->successMessage($product->getTitle() . ' could not be deleted as orders have been placed for this item. The product has been deactivated.', true);
        }

        header('Location: /' . $this->config->get('site.admin_uri') . '/shop/category/' . $product->getCategoryId());
        exit();
    }

    //activate-product
    public function activateProduct($productId)
    {
        $product = $this->productStore->getById($productId);
        $product->setActive(1);
        $this->productStore->save($product);
        $this->successMessage($product->getTitle() . ' was activated successfully.', true);

        header('Location: /' . $this->config->get('site.admin_uri') . '/shop/category/' . $product->getCategoryId());
        exit();
    }

    public function deactivateProduct($productId)
    {
        $product = $this->productStore->getById($productId);
        $product->setActive(0);
        $this->productStore->save($product);
        $this->successMessage($product->getTitle() . ' was deactivated successfully.', true);

        header('Location: /' . $this->config->get('site.admin_uri') . '/shop/category/' . $product->getCategoryId());
        exit();
    }

    public function productVariants($productId)
    {
        $product = $this->productStore->getById($productId);

        $this->view->product = $product;
        $this->view->availableVariants = $this->variantStore->getVariantsNotUsedByProduct($productId);

        $this->view->items = $this->itemVariantStore->getAllForItem($productId);

        $this->setTitle($product->getTitle() . ' Variants');
        $this->addBreadcrumb($product->getTitle(), '/shop/edit-product/' . $product->getId());
        $this->addBreadcrumb('Variants', '/shop/product-variants/' . $product->getId());

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

        $this->view->variants = $variants;

        if ($this->request->getMethod() == 'POST') {
            if ($this->getParam('price')) {
                foreach ($this->getParam('price') as $itemVariantId => $priceAdjustment) {
                    $itemVariant = $this->itemVariantStore->getById($itemVariantId);
                    $itemVariant->setPriceAdjustment($priceAdjustment);
                    $this->itemVariantStore->save($itemVariant);
                }
            }

            if ($this->getParam('new_variant')) {
                $variant = $this->variantStore->getById($this->getParam('new_variant'));
                $options = $this->variantOptionStore->getByVariantId($variant->getId());

                foreach ($options as $option) {
                    $iv = new ItemVariant();
                    $iv->setVariantId($variant->getId());
                    $iv->setVariantOptionId($option->getId());
                    $iv->setItemId($productId);
                    $this->itemVariantStore->save($iv);
                }
            }

            $this->successMessage('The variants were updated succcessfully.', true);
            header('Location: /' . $this->config->get('site.admin_uri') . '/shop/product-variants/' . $productId);
            exit();
        }
    }

    /**
     * Manage related products - You may also like...
     * @param $productId
     */
    public function productRelated($productId)
    {
        $product = $this->productStore->getById($productId);

        $this->view->product = $product;
        $this->view->availableVariants = $this->variantStore->getVariantsNotUsedByProduct($productId);

        $this->view->items = $this->itemVariantStore->getAllForItem($productId);

        $this->setTitle('Manage related products for: ' . $product->getCategory()->getName() .' / '. $product->getTitle());
        $this->addBreadcrumb($product->getTitle(), '/shop/edit-product/' . $product->getId());
        $this->addBreadcrumb('Related', '/shop/product-related/' . $product->getId());

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

        $this->view->variants = $variants;

        if ($this->request->getMethod() == 'POST') {
            if ($this->getParam('price')) {
                foreach ($this->getParam('price') as $itemVariantId => $priceAdjustment) {
                    $itemVariant = $this->itemVariantStore->getById($itemVariantId);
                    $itemVariant->setPriceAdjustment($priceAdjustment);
                    $this->itemVariantStore->save($itemVariant);
                }
            }

            if ($this->getParam('new_variant')) {
                $variant = $this->variantStore->getById($this->getParam('new_variant'));
                $options = $this->variantOptionStore->getByVariantId($variant->getId());

                foreach ($options as $option) {
                    $iv = new ItemVariant();
                    $iv->setVariantId($variant->getId());
                    $iv->setVariantOptionId($option->getId());
                    $iv->setItemId($productId);
                    $this->itemVariantStore->save($iv);
                }
            }

            $this->successMessage('The variants were updated succcessfully.', true);
            header('Location: /' . $this->config->get('site.admin_uri') . '/shop/product-variants/' . $productId);
            exit();
        }
    }

    public function productForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/shop/add-product');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/shop/edit-product/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('title');
        $field->setRequired(true);
        $field->setLabel('Title');
        $fieldset->addField($field);

        $field = new Form\Element\Select('category_id');
        $field->setOptions($this->categoryStore->getNamesForScope('shop'));
        $field->setLabel('Category');
        $field->setClass('select2');
        $fieldset->addField($field);

        $field = new Form\Element\TextArea('short_description');
        $field->setRequired(false);
        $field->setLabel('Short Description (optional)');
        $fieldset->addField($field);

        $field = new Form\Element\TextArea('description');
        $field->setRequired(false);
        $field->setLabel('Description');
        $field->setClass('ckeditor advanced');
        $fieldset->addField($field);

        $field = new Form\Element\Text('price');
        $field->setRequired(true);
        $field->setLabel('Price');
        $fieldset->addField($field);


        $mm = $this->config->get('ModuleManager');
        if($mm->isEnabled('FulfilmentHouse')) {
            $field = new Form\Element\Select('fulfilment_house_id');
            $field->setOptions($this->supplierStore->getOptions());
            $field->setLabel('Supplier');
            $field->setClass('select2');
            $fieldset->addField($field);
        }

        $field = new Form\Element\Text('expiry_date');
        $field->setRequired(false);
        $field->setLabel('Expiry Date');
        $field->setClass('datepicker');
        $fieldset->addField($field);

        if ($type == 'add') {
            $field = new ImageUpload('images[]');
            $field->setRequired(true);
            $field->setMultiple();
            $field->setLabel('Images (upload new images from your computer)');
            $fieldset->addField($field);
        }

        $field = new Form\Element\Submit();
        $field->setValue('Save Product');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

    public function updateImagePositions()
    {
        // Are we updating menu positions?
        $items = $this->getParam('positions', null);
        $itemId = $this->getParam('item_id');

        if (!is_null($items)) {
            foreach ($items as $fileId => $position) {
                $item = $this->itemFileStore->getByItemIdAndFileId($itemId, $fileId);
                $item->setPosition($position);
                $this->itemFileStore->save($item);
            }
        }
    }
}
