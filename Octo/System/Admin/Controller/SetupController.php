<?php

namespace Octo\System\Admin\Controller;

use Octo\Admin\Controller;
use Octo\Store;
use Octo\System\Model\User;
use Octo\System\Store\UserStore;

class SetupController extends Controller
{
    /** @var  UserStore */
    protected $userStore;

    public function init()
    {
        parent::init();
        $this->userStore = Store::get('User');
    }

    public function index()
    {
        if ($this->userStore->find()->count() !== 0) {
            return $this->redirect('/manage')->error('Setup has already been run, please log in to continue.');
        }

        if ($this->request->getMethod() == 'POST' && $this->validate()) {
            $user = new User();
            $user->setName($this->getParam('name'));
            $user->setEmail($this->getParam('email'));
            $user->setHash(password_hash($this->getParam('password'), PASSWORD_DEFAULT));
            $user->setDateAdded(new \DateTime());
            $user->setDateActive(new \DateTime());
            $user->setIsAdmin(1);

            $user = $this->userStore->save($user);
            $_SESSION['user_id'] = $user->getId();

            return $this->redirect('/')->success('Welcome to Octo, ' . $user->getName());
        }
    }

    protected function validate()
    {
        if (empty($this->getParam('name'))) {
            $this->template->error = 'Please enter your name.';
            return false;
        }

        if (empty($this->getParam('email'))) {
            $this->template->error = 'Please enter your email address.';
            return false;
        }

        if (empty($this->getParam('password'))) {
            $this->template->error = 'Please enter your password.';
            return false;
        }

        return true;
    }
}