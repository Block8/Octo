<?php
namespace Octo\Shop\Admin\Controller;

use b8\Form;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Shop\Model\Discount;
use Octo\Store;

class DiscountController extends Controller
{
    /**
     * @var \Octo\Shop\Store\DiscountStore
     */
    protected $discountStore;

    public function init()
    {
        $this->discountStore = Store::get('Discount');

        $this->setTitle('Discounts');
        $this->addBreadcrumb('Shop', '/shop');
        $this->addBreadcrumb('Discounts', '/discount');
    }

    public function index()
    {
        $this->setTitle('Manage Discounts');
        $this->view->discounts = $this->discountStore->getAll();
    }

    public function add()
    {
        $this->setTitle('Add Discount', 'Products');
        $this->addBreadcrumb('Add Discount', '/discount/add');
        $this->view->form = $this->discountForm();

        if ($this->request->getMethod() == 'POST') {
            $form = $this->discountForm($this->getParams());

            if ($form->validate()) {
                try {
                    $discount = new Discount();
                    $params = $this->getParams();
                    $discount->setValues($params);

                    $discount = $this->discountStore->save($discount);

                    $this->successMessage('Discount ' . $discount->getTitle() . ' was added successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/discount');
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the discount. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error adding the discount. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->discountForm();
            $this->view->form = $form->render();
        }
    }

    public function edit($discountId)
    {
        $discount = $this->discountStore->getById($discountId);
        $this->view->discount = $discount;

        $this->addBreadcrumb('Edit Discount', '/discount/edit/' . $discountId);

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $discountId], $this->getParams());
            $form = $this->discountForm($values, 'edit');

            if ($form->validate()) {
                try {
                    $discount->setValues($values);
                    $discount = $this->discountStore->save($discount);

                    $this->successMessage('Discount ' . $discount->getTitle() . ' was edited successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/discount');
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error editing the discount. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error editing the discount. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $values = array_merge(['id' => $discountId], $discount->getDataArray());
            $form = $this->discountForm($values, 'edit');
            $this->view->form = $form->render();
        }
    }

    public function delete($discountId)
    {
        $discount = $this->discountStore->getById($discountId);
        $this->discountStore->delete($discount);

        $this->successMessage('Discount ' . $discount->getTitle() . ' was deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/discount/');
        exit();
    }

    public function activate($discountId)
    {
        $discount = $this->discountStore->getById($discountId);
        $discount->setActive(1);
        $this->discountStore->save($discount);
        $this->successMessage($discount->getTitle() . ' was activated successfully.', true);

        header('Location: /' . $this->config->get('site.admin_uri') . '/discount');
        exit();
    }

    public function deactivate($discountId)
    {
        $discount = $this->discountStore->getById($discountId);
        $discount->setActive(0);
        $this->discountStore->save($discount);
        $this->successMessage($discount->getTitle()  . ' was deactivated successfully.', true);

        header('Location: /' . $this->config->get('site.admin_uri') . '/discount');
        exit();
    }

    public function discountForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/discount/add');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/discount/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('title');
        $field->setRequired(true);
        $field->setLabel('Title');
        $fieldset->addField($field);

        $field = new Form\Element\Text('description');
        $field->setRequired(false);
        $field->setLabel('Short Description');
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Discount');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }
}
