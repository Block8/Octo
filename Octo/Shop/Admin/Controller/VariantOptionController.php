<?php
namespace Octo\Shop\Admin\Controller;

use b8\Form;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Shop\Model\Variant;
use Octo\Shop\Model\VariantOption;
use Octo\Store;

class VariantOptionController extends Controller
{

    /**
     * @var \Octo\Shop\Store\VariantStore
     */
    protected $variantStore;
    /**
     * @var \Octo\Shop\Store\VariantOptionStore
     */
    protected $variantOptionStore;

    public function init()
    {
        $this->variantStore = Store::get('Variant');
        $this->variantOptionStore = Store::get('VariantOption');

        $this->setTitle('Variant Options');
        $this->addBreadcrumb('Products', '/product');
        $this->addBreadcrumb('Variants', '/variant');
    }

    public function manage($variantId)
    {
        $variant = $this->variantStore->getById($variantId);
        $this->addBreadcrumb($variant->getTitle(), '/variant/' . $variantId);
        $this->addBreadcrumb('Options', '/variant-option/manage/' . $variantId);

        $this->view->variant = $variant;
        $this->view->variantOptions = $this->variantOptionStore->getByVariantId(
            $variantId,
            ['order' => [['position', 'ASC'], ['id', 'ASC']]]
        );
    }

    public function add($variantId)
    {
        $variant = $this->variantStore->getById($variantId);
        $this->addBreadcrumb($variant->getTitle(), '/variant/' . $variantId);
        $this->addBreadcrumb('Options', '/variant-option/manage/' . $variantId);
        $this->addBreadcrumb('Add Option', '/variant-option/add/' . $variantId);

        $this->view->variant = $variant;

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['variant_id' => $variantId], $this->getParams());
            $form = $this->variantOptionForm($values);

            if ($form->validate()) {
                try {
                    $variantOption = new VariantOption();
                    $params = $this->getParams();
                    $variantOption->setValues($params);
                    $variantOption->setVariantId($variantId);

                    $variantOption = $this->variantOptionStore->save($variantOption);

                    $this->successMessage($variantOption->getOptionTitle() . ' was added successfully.', true);

                    $redirect = $this->config->get('site.admin_uri') . '/variant-option/manage/' . $variantId;
                    header('Location: /' . $redirect);
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the variant option. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error adding the variant option. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->variantOptionForm(['variant_id' => $variantId]);
            $this->view->form = $form->render();
        }
    }

    public function edit($variantOptionId)
    {
        $variantOption = $this->variantOptionStore->getById($variantOptionId);
        $variant = $this->variantStore->getById($variantOption->getVariantId());
        $this->view->variant = $variant;
        $this->view->variantOption = $variantOption;

        $this->addBreadcrumb($variant->getTitle(), '/variant/' . $variant->getId());
        $this->addBreadcrumb('Options', '/variant-option/manage/' . $variant->getId());
        $this->addBreadcrumb($variantOption->getOptionTitle(), '/variant-option/edit/' . $variantOption->getId());

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(['id' => $variantOptionId], $this->getParams());
            $form = $this->variantOptionForm($this->getParams(), 'edit');

            if ($form->validate()) {
                try {
                    $variantOption->setValues($values);
                    $variantOption = $this->variantOptionStore->save($variantOption);

                    $this->successMessage($variantOption->getOptionTitle() . ' was edited successfully.', true);
                    $adminUri = $this->config->get('site.admin_uri');
                    $redirect = $adminUri . '/variant-option/manage/' . $variantOption->getVariantId();
                    header('Location: /' . $redirect);
                    exit();
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error editing the variant option. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error editing the variant option. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $values = array_merge(['id' => $variantOptionId], $variantOption->getDataArray());
            $form = $this->variantOptionForm($values, 'edit');
            $this->view->form = $form->render();
        }
    }

    public function delete($variantOptionId)
    {
        $variantOption = $this->variantOptionStore->getById($variantOptionId);
        $this->variantOptionStore->delete($variantOption);

        $this->successMessage($variantOption->getOptionTitle() . ' was deleted successfully.', true);

        $adminUri = $this->config->get('site.admin_uri');
        header('Location: /' . $adminUri . '/variant-option/manage/' . $variantOption->getVariantId());
        exit();
    }

    public function variantOptionForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        $adminUri = $this->config->get('site.admin_uri');
        if ($type == 'add') {
            $form->setAction('/' . $adminUri . '/variant-option/add/' . $values['variant_id']);
        } else {
            $form->setAction('/' . $adminUri . '/variant-option/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('option_title');
        $field->setRequired(true);
        $field->setLabel('Option');
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Variant Option');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

    public function updatePositions()
    {
        // Are we updating positions?
        $options = $this->getParam('positions', null);

        if (!is_null($options)) {
            foreach ($options as $variantOptionId => $position) {
                $variantOption = $this->variantOptionStore->getById($variantOptionId);
                $variantOption->setPosition($position);
                $this->variantOptionStore->save($variantOption);
            }
        }
    }
}
