<?php
namespace Octo\Shop\Admin\Controller;

use b8\Form;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Shop\Model\Variant;
use Octo\Store;

class VariantController extends Controller
{

    /**
     * @var \Octo\Shop\Store\VariantStore
     */
    protected $variantStore;

    public function init()
    {
        $this->variantStore = Store::get('Variant');

        $this->setTitle('Variants');
        $this->addBreadcrumb('Products', '/product');
        $this->addBreadcrumb('Variants', '/variant');
    }

    public function index()
    {
        $this->setTitle('Manage Variants');
        $this->view->variants = $this->variantStore->getAll();
    }

    public function add()
    {
        $this->setTitle('Add Variant', 'Products');
        $this->addBreadcrumb('Add Variant', '/variant/add');
        $this->view->form = $this->variantForm();

        if ($this->request->getMethod() == 'POST') {
            $form = $this->variantForm($this->getParams());

            if ($form->validate()) {
                try {
                    $variant = new Variant();
                    $params = $this->getParams();
                    $variant->setValues($params);

                    $variant = $this->variantStore->save($variant);

                    $this->successMessage('Variant ' . $variant->getTitle() . ' was added successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/variant');
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the variant. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error adding the variant. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->variantForm();
            $this->view->form = $form->render();
        }
    }

    public function edit($variantId)
    {
        $variant = $this->variantStore->getById($variantId);
        $this->view->variant = $variant;

        $this->addBreadcrumb('Edit Variant', '/variant/edit/' . $variantId);

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $variantId], $this->getParams());
            $form = $this->variantForm($this->getParams(), 'edit');

            if ($form->validate()) {
                try {
                    $variant->setValues($values);
                    $variant = $this->variantStore->save($variant);

                    $this->successMessage('Variant ' . $variant->getTitle() . ' was edited successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/variant');
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error editing the variant. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error editing the variant. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $values = array_merge(['id' => $variantId], $variant->getDataArray());
            $form = $this->variantForm($values, 'edit');
            $this->view->form = $form->render();
        }
    }

    public function delete($variantId)
    {
        $variant = $this->variantStore->getById($variantId);
        $this->variantStore->delete($variant);

        $this->successMessage('Variant ' . $variant->getTitle() . ' was deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/variant/');
        exit();
    }

    public function variantForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/variant/add');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/variant/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('title');
        $field->setRequired(true);
        $field->setLabel('Title');
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Variant');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }
}
