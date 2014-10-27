<?php
namespace Octo\Shop\Admin\Controller;

use b8\Form;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Shop\Model\Discount;
use Octo\Shop\Model\DiscountOption;
use Octo\Store;

class DiscountOptionController extends Controller
{

    /**
     * @var \Octo\Shop\Store\DiscountStore
     */
    protected $discountStore;
    /**
     * @var \Octo\Shop\Store\DiscountOptionStore
     */
    protected $discountOptionStore;

    public function init()
    {
        $this->discountStore = Store::get('Discount');
        $this->discountOptionStore = Store::get('DiscountOption');

        $this->setTitle('Discount Options');
        $this->addBreadcrumb('Shop', '/shop');
        $this->addBreadcrumb('Discounts', '/discount');
    }

    public function manage($discountId)
    {
        $discount = $this->discountStore->getById($discountId);
        $this->addBreadcrumb($discount->getTitle(), '/discount/edit/' . $discountId);
        $this->addBreadcrumb('Options', '/discount-option/manage/' . $discountId);

        $this->view->discount = $discount;
        $this->view->discountOptions = $this->discountOptionStore->getByDiscountId($discountId);
    }

    public function add($discountId)
    {
        $discount = $this->discountStore->getById($discountId);
        $this->addBreadcrumb($discount->getTitle(), '/discount/edit/' . $discountId);
        $this->addBreadcrumb('Options', '/discount-option/manage/' . $discountId);
        $this->addBreadcrumb('Add Option', '/discount-option/add/' . $discountId);

        $this->view->discount = $discount;

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['discount_id' => $discountId], $this->getParams());
            $form = $this->discountOptionForm($values);

            if ($form->validate()) {
                try {
                    $discountOption = new DiscountOption();
                    $params = $this->getParams();
                    $discountOption->setValues($params);
                    $discountOption->setDiscountId($discountId);

                    $discountOption = $this->discountOptionStore->save($discountOption);

                    $this->successMessage($discount->getOptionTitle() . ' was added successfully.', true);

                    $redirect = $this->config->get('site.admin_uri') . '/discount-option/manage/' . $discountId;
                    header('Location: /' . $redirect);
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the discount option. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error adding the discount option. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->discountOptionForm(['discount_id' => $discountId]);
            $this->view->form = $form->render();
        }
    }

    public function edit($discountOptionId)
    {
        $discountOption = $this->discountOptionStore->getById($discountOptionId);
        $discount = $this->discountStore->getById($discountOption->getDiscountId());
        $this->view->discount = $discount;
        $this->view->discountOption = $discountOption;

        $this->addBreadcrumb($discount->getTitle(), '/discount/edit/' . $discount->getId());
        $this->addBreadcrumb('Options', '/discount-option/manage/' . $discount->getId());
        $this->addBreadcrumb($discountOption->getAmountInitial() .'-'.$discountOption->getAmountFinal(), '/discount-option/edit/' . $discountOption->getId());

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $discountOptionId], $this->getParams());

            $form = $this->discountOptionForm($values, 'edit');

            if ($form->validate()) {
                try {
                    $discountOption->setValues($values);
                    $discountOption = $this->discountOptionStore->save($discountOption);

                    $this->successMessage('Option ' .$discountOption->getAmountInitial() .'-'.$discountOption->getAmountFinal() . ' was edited successfully.', true);
                    $adminUri = $this->config->get('site.admin_uri');
                    $redirect = $adminUri . '/discount-option/manage/' . $discountOption->getDiscountId();
                    header('Location: /' . $redirect);
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error editing the discount option. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error editing the discount option. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $values = array_merge(['id' => $discountOptionId], $discountOption->getDataArray());
            $form = $this->discountOptionForm($values, 'edit');
            $this->view->form = $form->render();
        }
    }

    public function delete($discountOptionId)
    {
        $discountOption = $this->discountOptionStore->getById($discountOptionId);
        $this->discountOptionStore->delete($discountOption);

        $this->successMessage($discountOption->getOptionTitle() . ' was deleted successfully.', true);

        $adminUri = $this->config->get('site.admin_uri');
        header('Location: /' . $adminUri . '/discount-option/manage/' . $discountOption->getDiscountId());
        exit();
    }

    public function discountOptionForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        $adminUri = $this->config->get('site.admin_uri');
        if ($type == 'add') {
            $form->setAction('/' . $adminUri . '/discount-option/add/' . $values['discount_id']);
        } else {
            $form->setAction('/' . $adminUri . '/discount-option/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('amount_initial');
        $field->setRequired(true);
        $field->setLabel('Initial Amount');
        $fieldset->addField($field);

        $field = new Form\Element\Text('amount_final');
        $field->setRequired(true);
        $field->setLabel('Final Amount');
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Discount Option');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

}
