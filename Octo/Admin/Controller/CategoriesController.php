<?php
namespace Octo\Admin\Controller;

use b8\Form;
use Octo\Store;
use Octo\Store\CategoryStore;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu;
use Octo\Model\Category;
use Octo\Utilities\StringUtilities;

class CategoriesController extends Controller
{

    /**
     * @var CategoryStore
     */
    protected $categoryStore;

    /**
     * Setup initial menu
     *
     * @return void
     * @author James Inman
     */
    public function init()
    {
        $this->categoryStore = Store::get('Category');
    }

    public function categoryList($scope)
    {
        $categories = $this->categoryStore->getAllForScope($scope);

        $rtn = [];
        foreach ($categories['items'] as $category) {
            $rtn[$category->getId()] = $category->getName();
        }

        die(json_encode($rtn));
    }

    public function manage($scope, $useBase = false)
    {
        $scope_name = ucwords($scope);

        $type = [];

        $base = '';
        if ((bool) $useBase) {
            $base = '/base';

            $type['singular'] = StringUtilities::singularize($scope_name);
            $type['plural'] = $scope_name;

            $this->setTitle($scope_name);
            $this->addBreadcrumb($scope_name, '/categories/manage/' . $scope . $base);
        } else {
            $type['singular'] = 'Category';
            $type['plural'] = 'Categories';

            $this->setTitle($scope_name . ' Categories');
            $this->addBreadcrumb($scope_name, '/' . $scope);
            $this->addBreadcrumb('Categories', '/categories/manage/' . $scope);
        }

        $this->view->type = $type;
        $this->view->useBase = $useBase;

        $this->view->scope = $scope;

        if ($this->getParam('parent')) {
            $parentId = $this->getParam('parent');
            $parent = $this->categoryStore->getById($parentId);
            $this->view->categories = $this->categoryStore->getAllForParent($parentId);
            $this->addBreadcrumb($parent->getName(), '/categories/manage/' . $scope . $base . '?parent=' . $parentId);
        } else {
            $this->view->categories = $this->categoryStore->getAllForScope($scope);
        }
    }

    public function add($scope, $useBase = false)
    {
        $scope_name = ucwords($scope);

        $type = [];
        if ((bool) $useBase) {
            $type['singular'] = StringUtilities::singularize($scope_name);
            $type['plural'] = $scope_name;

            $this->setTitle('Add ' . $type['singular']);
            $this->addBreadcrumb($scope_name, '/categories/add/' . $scope . '/base');
        } else {
            $type['singular'] = 'Category';
            $type['plural'] = 'Categories';

            $this->setTitle($scope_name . ' Categories');
            $this->addBreadcrumb($scope_name, '/' . $scope);
            $this->addBreadcrumb('Categories', '/categories/add/' . $scope);
        }

        $this->view->type = $type;

        if ($this->request->getMethod() == 'POST') {
            $form = $this->categoryForm($scope, $this->getParams(), 'add', $useBase);

            if ($form->validate()) {
                try {
                    $category = new Category();
                    $category->setValues($this->getParams());
                    $category->setSlug(StringUtilities::generateSlug($category->getName()));
                    $category->setScope($scope);

                    if ($category->getParentId() == 0) {
                        $category->setParentId(null);
                    }

                    $category = $this->categoryStore->save($category);

                    $this->successMessage($category->getName() . ' was added successfully.', true);
                    if ($useBase) {
                        header('Location: /' . $this->config->get('site.admin_uri') . '/categories/manage/' . $scope . '/base');
                    } else {
                        header('Location: /' . $this->config->get('site.admin_uri') . '/categories/manage/' . $scope);
                    }
                } catch (Exception $e) {
                    $this->errorMessage('There was an error adding the category. Please try again.');
                }
            } else {
                $this->errorMessage('There was an error adding the category. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->categoryForm($scope, array(), 'add', $useBase);
            $this->view->form = $form->render();
        }
    }

    public function edit($scope, $categoryId, $useBase = false)
    {
        $scope_name = ucwords($scope);

        $type = [];
        if ((bool) $useBase) {
            $type['singular'] = StringUtilities::singularize($scope_name);
            $type['plural'] = $scope_name;

            $this->setTitle('Edit ' . $type['singular']);
            $this->addBreadcrumb($scope_name, '/categories/edit/' . $scope . '/base');
        } else {
            $type['singular'] = 'Category';
            $type['plural'] = 'Categories';

            $this->setTitle($scope_name . ' Categories');
            $this->addBreadcrumb($scope_name, '/' . $scope);
            $this->addBreadcrumb('Categories', '/categories/edit/' . $scope);
        }

        $category = $this->categoryStore->getById($categoryId);

        $this->view->type = $type;

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge($this->getParams(), array('id' => $categoryId));
            $form = $this->categoryForm($scope, $values, 'edit', $useBase);

            if ($form->validate()) {
                try {
                    $category->setValues($this->getParams());

                    if ($category->getParentId() == 0) {
                        $category->setParentId(null);
                    }

                    // Later, we might want to change the slug if there's nothing in the category already
                    // or fix URLs, or something. [JI - 21/02/14]
                    $category = $this->categoryStore->save($category);

                    $this->successMessage($category->getName() . ' was edited successfully.', true);
                    if ($useBase) {
                        header('Location: /' . $this->config->get('site.admin_uri') . '/categories/manage/' . $scope . '/base');
                    } else {
                        header('Location: /' . $this->config->get('site.admin_uri') . '/categories/manage/' . $scope);
                    }
                } catch (Exception $e) {
                    $this->errorMessage('There was an error editing the category. Please try again.');
                }
            } else {
                $this->errorMessage('There was an error editing the category. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->categoryForm($scope, $category->getDataArray(), 'edit', $useBase);
            $this->view->form = $form->render();
        }
    }

    public function delete($scope, $categoryId, $useBase = false)
    {
        $category = $this->categoryStore->getById($categoryId);
        $this->categoryStore->delete($category);
        $this->successMessage($category->getName() . ' was deleted successfully.', true);

        if ($useBase) {
            header('Location: /' . $this->config->get('site.admin_uri') . '/categories/manage/' . $scope . '/base');
        } else {
            header('Location: /' . $this->config->get('site.admin_uri') . '/categories/manage/' . $scope);
        }
    }

    protected function categoryForm($scope, $values = [], $type = 'add', $useBase = false)
    {
        $form = new FormElement();
        $form->setMethod('POST');

        $scope_name = ucwords($scope);

        $base = '';
        if ((bool) $useBase) {
            $base = '/base';
        }

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/categories/add/' . $scope . $base);
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/categories/edit/' . $scope . '/' . $values['id'] . $base);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('name');
        $field->setRequired(true);
        $field->setLabel('Name');
        $fieldset->addField($field);

        $field = new Form\Element\Select('parent_id');
        $field->setClass('select2');
        $field->setLabel('Parent');

        $options = [];
        $options[] = '---';
        $options = $options + $this->categoryStore->getNamesForParents('blog');
        $field->setOptions($options);
        $field->setRequired(false);

        if ($type =='edit') {
            $field->setValue($values['parent_id']);
        }

        $fieldset->addField($field);

        if ((bool) $useBase) {
            $field = new Form\Element\Submit();
            $field->setValue('Save ' . StringUtilities::singularize($scope_name));
            $field->setClass('btn-success');
            $fieldset->addField($field);
        } else {
            $field = new Form\Element\Submit();
            $field->setValue('Save Category');
            $field->setClass('btn-success');
            $fieldset->addField($field);
        }

        $form->setValues($values);
        return $form;
    }
}
