<?php

namespace Octo\Invoicing\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Admin;
use Octo\Admin\Menu;
use Octo\Admin\Form as FormElement;
use Octo\Invoicing\Model\Item;
use Octo\Event;
use Octo\Store;

class ItemController extends Admin\Controller
{
    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $store;

    /**
     * Set up menus for this controller.
     * @param \Octo\Admin\Menu $menu
     */
    public static function registerMenus(Menu $menu)
    {
        if (is_null(Store::get('Variant'))) {
            $thisMenu = $menu->addRoot('Invoice Items', '/item')->setIcon('qrcode');
            $thisMenu->addChild(new Menu\Item('Add Item', '/item/add'));

            $manage = new Menu\Item('Manage Items', '/item');
            $manage->addChild(new Menu\Item('Edit Item', '/item/edit', true));
            $manage->addChild(new Menu\Item('Delete Item', '/item/delete', true));
            $thisMenu->addChild($manage);
        }
    }

    /**
     * Set up the controller
     */
    public function init()
    {
        $this->store = Store::get('Item');
        $this->addBreadcrumb('Invoice Items', '/item');
    }

    public function index()
    {
        $this->setTitle('Manage Items', 'Invoicing');
        $this->view->items = $this->store->getAll();
    }

    public function add()
    {
        $this->setTitle('Add Item', 'Invoicing');
        $this->addBreadcrumb('Add Item', '/item/add');
        $form = $this->editForm($this->getParams());

        if ($this->request->getMethod() == 'POST') {
            if ($form->validate()) {
                try {
                    $item = new Item();
                    $item->setValues($this->getParams());
                    $item->setCreatedDate(new \DateTime());
                    $item->setUpdatedDate(new \DateTime());

                    if (Event::trigger('BeforeItemSave', $item)) {
                        $item = $this->store->save($item);
                    }

                    Event::trigger('OnItemSave', $item);
                    $this->successMessage('Item added successfully.', true);
                    $this->response = new RedirectResponse();
                    $this->response->setHeader('Location', '/' . $this->config->get('site.admin_uri') . '/item');
                    return;
                } catch (Exception $e) {
                    $this->errorMessage('There was an error, please try again.');
                }
            }

            $this->errorMessage('There was an error, please try again.');
        }

        $this->view->form = $form->render();
    }

    public function edit($key)
    {
        $item = $this->store->getByPrimaryKey($key);
        $this->view->item = $item;
        $this->setTitle($item->getTitle(), 'Edit Item');
        $this->addBreadcrumb('Edit Item', '/item/edit/' . $key);

        // Set up form:
        $values = array_merge($item->getDataArray(), $this->getParams());
        $form = $this->editForm($values, 'edit');

        if ($this->request->getMethod() == 'POST') {
            if ($form->validate()) {
                try {
                    $item->setValues($values);
                    $item->setUpdatedDate(new \DateTime());

                    if (Event::trigger('BeforeItemSave', $item)) {
                        $item = $this->store->save($item);
                    }

                    Event::trigger('OnItemSave', $item);

                    $this->successMessage('Item edited successfully.', true);
                    $this->response = new RedirectResponse();
                    $this->response->setHeader('Location', '/' . $this->config->get('site.admin_uri') . '/item');
                    return;
                } catch (Exception $e) {
                    $this->errorMessage('There was an error, please try again.');
                }
            } else {
                $this->errorMessage('There was an error, please try again.');
            }
        }

        $this->view->form = $form->render();
    }

    public function delete($key)
    {
        $item = $this->store->getByPrimaryKey($key);

        if ($item) {
            $this->store->delete($item);
        }

        $this->successMessage('Item deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/item/');
    }

    public function editForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/item/add');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/item/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);
        $field = Form\Element\Text::create('title', 'Title', true);
        $fieldset->addField($field);

        $field = Form\Element\Text::create('price', 'Price', true);
        $fieldset->addField($field);

        $field = new Form\Element\Select('category_id');
        $field->setOptions(Store::get('Category')->getNamesForScope('shop'));
        $field->setLabel('Category');
        $field->setClass('select2');
        $fieldset->addField($field);

        $field = Form\Element\Text::create('short_description', 'Short Description', false);
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Item');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

    public function autocomplete()
    {
        $items = Store::get('Item')->search($this->getParam('q', ''));

        $rtn = ['results' => [], 'more' => false];

        foreach ($items as $item) {
            $rtn['results'][] = [
                'id' => $item->getId(),
                'text' => $item->getTitle(),
                'short_description' => $item->getShortDescription(),
                'price' => $item->getPrice(),
            ];
        }

        die(json_encode($rtn));
    }
}
