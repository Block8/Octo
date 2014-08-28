<?php
namespace Octo\FulfilmentHouse\Admin\Controller;

use b8\Form;
use Octo\Admin;
use Octo\Admin\Menu;
use Octo\Admin\Form as FormElement;
use HMUK\FulfilmentHouse\Model\FulfilmentHouse;
use Octo\Event;
use Octo\Store;

class FulfilmentHouseController extends Admin\Controller
{
    private $fulfilmentHouseStore;

    /**
     * Return the menu nodes required for this controller
     *
     * @param  Menu $menu
     * @return void
     * @author George Gardiner
     */
    public static function registerMenus(Menu $menu)
    {
        $fuho = $menu->addRoot('Fulfilment', '#')->setIcon('gbp');
        $fuho->addChild(new Menu\Item('Add Supplier', '/fulfilment-house/add'));
        $fuho->addChild(new Menu\Item('Manage Suppliers', '/fulfilment-house/index'));
    }

    /**
     * Setup initial menu
     *
     * @return void
     * @author George Gardiner
     */
    public function init()
    {
        $this->setTitle('Fulfilment Houses');
        $this->addBreadcrumb('Fulfilment Houses', '/fulfilment-house');
        $this->fulfilmentHouseStore = Store::get('FulfilmentHouse');
    }

    /**
     * Add a Grant
     *
     * @return void
     * @author George Gardiner
     */
    public function add()
    {
        $this->setTitle('Add Fulfilment House');
        $this->addBreadcrumb('Add Fulfilment House', '/fulfilment-house/add');

        if ($this->request->getMethod() == 'POST') {
            $form = $this->supplierForm($this->getParams());

            if ($form->validate()) {
                try {
                    $supplier = new FulfilmentHouse();
                    $supplier->setValues($this->getParams());
                    $supplier = $this->fulfilmentHouseStore->save($supplier);

                    $this->successMessage($supplier->getName() . ' was added successfully.', true);
                    header('Location: /backoffice/fulfilment-house');
                } catch (Exception $e) {
                    $this->errorMessage('There was an error adding the supplier. Please try again.');
                }
            } else {
                $this->errorMessage('There was an error adding the supplier. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->supplierForm();
            $this->view->form = $form->render();
        }
    }

    /**
     * Manage Suppliers
     *
     * @return void
     * @author George gardiner
     */
    public function index()
    {
        $this->setTitle('Manage Suppliers', 'Fulfilment Houses');
        $this->view->suppliers = $this->fulfilmentHouseStore->getAll();
    }

    /**
     * Delete Grant
     *
     * @return void
     * @author George Gardiner
     */
    public function delete($supplierId)
    {
        $supplier = $this->fulfilmentHouseStore->getById($supplierId);
        $this->fulfilmentHouseStore->delete($supplier);
        $this->successMessage($supplier->getName() . ' was deleted successfully.', true);
        header('Location: /backoffice/fulfilment-house');
    }

    /**
     * Edit Fulfilment House
     *
     * @return void
     * @author George Gardiner
     */
    public function edit($supplierId)
    {
        $supplier = $this->fulfilmentHouseStore->getById($supplierId);
        $this->setTitle('Edit Supplier', 'Fulfilment Houses');

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(array('id' => $supplierId), $this->getParams());
            $form = $this->supplierForm($values, 'edit');

            if ($form->validate()) {
                try {
                    $supplier->setValues($this->getParams());
                    $supplier = $this->fulfilmentHouseStore->save($supplier);

                    $this->successMessage($supplier->getName() . ' was updated successfully.', true);
                    header('Location: /backoffice/fulfilment-house');
                } catch (Exception $e) {
                    $this->errorMessage('There was an error updating the supplier. Please try again.');
                }
            } else {
                $this->errorMessage('There was an error updating the supplier. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->supplierForm($supplier->getDataArray(), 'edit');
            $this->view->form = $form->render();
        }
    }

    public function supplierForm($values = array(), $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/backoffice/fulfilment-house/add');
        } else {
            $form->setAction('/backoffice/fulfilment-house/edit/' . $values['id']);
        }

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $fieldset->addField(Form\Element\Text::create('name', 'Supplier Name', true));
        $fieldset->addField(Form\Element\Text::create('email_1', 'Instruction Email Address #1', true));
        $fieldset->addField(Form\Element\Text::create('email_2', 'Instruction Email Address #2', false));
        $fieldset->addField(Form\Element\Text::create('email_3', 'Instruction Email Address #3', false));
        $fieldset->addField(Form\Element\TextArea::create('email_copy', 'Email Copy To Send', true));

        $field = new Form\Element\Submit();
        $field->setValue('Save Supplier');
        $field->setClass('btn-success');
        $fieldset->addField($field);

        $form->setValues($values);

        return $form;
    }



}
