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
use Octo\System\Model\File;;
use Octo\Store;
use Octo\Utilities\StringUtilities;

class ProductController extends Controller
{

    /**
     * @var \Octo\Shop\Store\ItemStore
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
     * Return the menu nodes required for this controller
     *
     * @param Menu $menu
     * @return void
     */
    public static function registerMenus(Menu $menu)
    {
        $products = $menu->addRoot('Products', '#')->setIcon('shopping-cart');
        $add = new Menu\Item('Add Product', '/product/add');
        $products->addChild($add);
        $manage = new Menu\Item('Manage Products', '/product');
        $manage->addChild(new Menu\Item('Edit Product', '/product/edit', true));
        $manage->addChild(new Menu\Item('Delete Product', '/product/delete', true));
        $manage->addChild(new Menu\Item('Manage Product Images', '/media/manage/shop', true));
        $manage->addChild(new Menu\Item('Add Product Image', '/media/add/shop', true));
        $manage->addChild(new Menu\Item('Edit Product Image', '/media/edit/shop', true));
        $manage->addChild(new Menu\Item('Delete Product Image', '/media/delete/shop', true));
        $products->addChild($manage);

        $categories = new Menu\Item('Manage Categories', '/categories/manage/shop');
        $products->addChild($categories);

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
        $this->productStore = Store::get('Item');
        $this->categoryStore = Store::get('Category');
        $this->itemFileStore = Store::get('ItemFile');
        $this->variantStore = Store::get('Variant');
        $this->variantOptionStore = Store::get('VariantOption');
        $this->itemVariantStore = Store::get('ItemVariant');
    }

    public function index()
    {
        $this->setTitle('Products');
        $this->addBreadcrumb('Products', '/product');

        $this->view->products = $this->productStore->getAll();
    }

    public function add()
    {
        $this->setTitle('Products');
        $this->addBreadcrumb('Add Product', '/product/add');
        $this->view->form = $this->productForm();

        if ($this->request->getMethod() == 'POST') {
            $form = $this->productForm($this->getParams());

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
                    header('Location: /' . $this->config->get('site.admin_uri') . '/product');
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

    public function edit($productId)
    {
        $product = $this->productStore->getById($productId);
        $this->view->product = $product;

        $this->setTitle($product->getTitle());
        $this->addBreadcrumb('Products', '/product');
        $this->addBreadcrumb($product->getTitle(), '/product/edit/' . $product->getId());

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $productId], $this->getParams());
            $form = $this->productForm($values, 'edit');

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
                    header('Location: /' . $this->config->get('site.admin_uri') . '/product');
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

    public function delete($productId)
    {
        $product = $this->productStore->getById($productId);
        $product->setActive(false);
        $this->productStore->save($product);

        $this->successMessage($product->getTitle() . ' was deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/product/');
    }

    public function variants($productId)
    {
        $product = $this->productStore->getById($productId);

        $this->view->product = $product;
        $this->view->variants = $this->variantStore->getVariantsNotUsedByProduct($productId);

        $this->setTitle($product->getTitle() . ' Variants');
        $this->addBreadcrumb('Products', '/product');
        $this->addBreadcrumb($product->getTitle(), '/product/edit/' . $product->getId());
        $this->addBreadcrumb('Variants', '/product/variants/' . $product->getId());

        if ($this->request->getMethod() == 'POST') {
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
        }
    }

    public function productForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/product/add');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/product/edit/' . $values['id']);
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

        $field = new Form\Element\Text('expiry_date');
        $field->setRequired(false);
        $field->setLabel('Expiry Date');
        $field->setClass('datepicker');
        $fieldset->addField($field);

        if ($type == 'add') {
            $field = new ImageUpload('images[]');
            $field->setRequired(true);
            $field->setMultiple();
            $field->setLabel('Images');
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
