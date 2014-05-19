<?php

namespace Octo\System\Admin\Controller;

use Exception;
use b8\Form;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu;
use Octo\Event;
use Octo\System\Model\User;
use Octo\System\Model\Permission;
use Octo\Store;

class UserController extends Controller
{
    /**
     * @var \Octo\System\Store\UserStore
     */
    protected $userStore;

    /**
     * @var \Octo\System\Store\PermissionStore
     */
    protected $permissionStore;


    public static function registerMenus(Menu $menu)
    {
        $users = $menu->addRoot('Users', '/user')->setIcon('user');
        $users->addChild(new Menu\Item('Add User', '/user/add'));

        $manage = new Menu\Item('Manage Users', '/user');
        $manage->addChild(new Menu\Item('Edit User', '/user/edit', true));
        $manage->addChild(new Menu\Item('Delete User', '/user/delete', true));
        $manage->addChild(new Menu\Item('Edit Permissions', '/user/permissions', true));
        $users->addChild($manage);
    }

    public function init()
    {
        $this->setTitle('Users');
        $this->addBreadcrumb('Users', '/user');
        $this->userStore = Store::get('User');
        $this->permissionStore = Store::get('Permission');
    }

    public function index()
    {
        $this->view->users = $this->userStore->getAll();
    }

    public function add()
    {
        $this->setTitle('Add User');
        $this->addBreadcrumb('Add User', '/user/add');

        if ($this->request->getMethod() == 'POST') {
            $form = $this->userForm($this->getParams());

            if ($form->validate()) {
                if ($this->userStore->getByEmail($this->getParam('email'))) {
                    $error = 'This email address already belongs to a registered user.';
                    $form->getChild('fieldset')->getChild('email')->setError($error);
                    $this->view->form = $form->render();
                    return;
                }

                try {
                    $user = new User();
                    $params = $this->getParams();
                    $params['hash'] = password_hash($params['password'], PASSWORD_DEFAULT);

                    $user->setValues($params);
                    $user->setDateAdded(new \DateTime());
                    $user = $this->userStore->save($user);

                    $permission = new Permission;
                    $permission->setUserId($user->getId());
                    $permission->setCanAccess(true);
                    $permission->setUri('/');
                    $this->permissionStore->save($permission);

                    $this->successMessage($params['name'] . ' was added successfully.', true);

                    header('Location: /' . $this->config->get('site.admin_uri') . '/user');
                } catch (Exception $e) {
                    $this->errorMessage('There was an error adding the user. Please try again.');
                }
            } else {
                $this->errorMessage('There was an error adding the user. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $this->view->form = $this->userForm(array())->render();
        }
    }

    public function edit($userId)
    {
        $user = $this->userStore->getById($userId);

        $this->setTitle('Edit User: ' . $user->getName());
        $this->addBreadcrumb($user->getName(), '/user/edit');

        if ($this->request->getMethod() == 'POST') {
            $form = $this->userForm($this->getParams(), 'edit');

            if ($form->validate()) {

                $emailChanged = ($this->getParam('email') !== $user->getEmail());
                if ($emailChanged && $this->userStore->getByEmail($this->getParam('email'))) {
                    $error = 'This email address already belongs to a registered user.';
                    $form->getChild('fieldset')->getChild('email')->setError($error);
                    $this->view->form = $form->render();
                    return;
                }

                try {
                    $params = $this->getParams();
                    
                    if ($params['password'] != '') {
                        $params['hash'] = password_hash($params['password'], PASSWORD_DEFAULT);
                    }
                    $user->setValues($params);
                    
                    $listenData = [$user, $params];
                    Event::trigger('beforeUserSave', $listenData);
                    list($user, $params) = $listenData;
                    
                    $user = $this->userStore->save($user);
                    $this->successMessage($params['name'] . ' was edited successfully.', true);

                    header('Location: /' . $this->config->get('site.admin_uri') . '/user');
                } catch (Exception $e) {
                    $this->errorMessage('There was an error editing the user. Please try again.');
                }

            } else {
                $this->errorMessage('There was an error editing the user. Please try again.');
            }
        }

        $this->view->form = $this->userForm($user->getDataArray(), 'edit')->render();
    }

    public function delete($userId)
    {
        $user = $this->userStore->getById($userId);
        $this->userStore->delete($user);
        $this->successMessage($user->getName() . ' was deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/user/');
    }

    protected function userForm($values, $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        if ($type == 'add') {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/user/add');
        } else {
            $form->setAction('/' . $this->config->get('site.admin_uri') . '/user/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        if (isset($values['id'])) {
            $field = new Form\Element\Hidden('id');
            $field->setRequired(true);
            $field->setValue($values['id']);
            $fieldset->addField($field);
        }

        $field = new Form\Element\Text('name');
        $field->setRequired(true);
        $field->setLabel('Name');
        $fieldset->addField($field);

        $field = new Form\Element\Email('email');
        $field->setRequired(true);
        $field->setLabel('Email Address');
        $fieldset->addField($field);

        $field = new Form\Element\Password('password');
        if ($type == 'add') {
            $field->setRequired(true);
        } else {
            $field->setRequired(false);
        }
        $field->setLabel('Password' . ($type == 'edit' ? ' (leave blank to keep current password)' : ''));
        $fieldset->addField($field);

        if ($this->currentUser->getIsAdmin()) {
            $field = new Form\Element\Select('is_admin');
            $field->setRequired(false);
            $field->setLabel('Administrator');
            $field->setOptions([0 => 'No', 1 => 'Yes']);
            $fieldset->addField($field);
        }
        
        $data = [&$form, &$values];
        Event::trigger('userForm', $data);
        list($form, $values) = $data;

        $fieldset = new Form\FieldSet('fieldset3');
        $form->addField($fieldset);
        
        $field = new Form\Element\Submit();
        $field->setValue('Save User');
        $field->setClass('btn-success');
        $fieldset->addField($field);

        $form->setValues($values);
        return $form;
    }

    public function permissions($userId)
    {
        $user = $this->userStore->getById($userId);

        if ($this->request->getMethod() == 'POST') {
            $perms = $this->getParams();

            foreach ($perms as &$perm) {
                if ($perm == 'on') {
                    $perm = 1;
                }
            }

            $this->permissionStore->updatePermissions($userId, $perms);
            $this->successMessage('Permissions updated successfully.');
        }

        $permissions = $this->permissionStore->getByUserId($userId);

        $this->view->user = $user;
        $this->view->permTree = $this->preparePermissionsTree($user, $this->menu->getTree(), $permissions);
    }

    /**
     * @param User $user
     * @param array $tree
     * @param Permission[] $permissions
     * @return array
     */
    protected function preparePermissionsTree($user, $tree, $permissions)
    {
        $perms = [];
        foreach ($permissions as $perm) {
            $perms[$perm->getUri()] = (bool)$perm->getCanAccess();
        }

        foreach ($tree as &$item) {
            $this->applyPermissionsToTreeItem($user, $item, $perms);
        }

        return $tree;
    }

    protected function applyPermissionsToTreeItem(User $user, &$item, $perms)
    {
        $item['can_access'] = false;

        if (array_key_exists($item['uri'], $perms) && $perms[$item['uri']]) {
            $item['can_access'] = true;
        }

        if (array_key_exists('children', $item)) {
            foreach ($item['children'] as &$child) {
                $this->applyPermissionsToTreeItem($user, $child, $perms);
            }
        }

        return $item;
    }
}
