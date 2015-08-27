<?php

namespace Octo;

use b8\Config;
use b8\Form\Element\Email;
use b8\Form\Element\Hidden;
use b8\Form\Element\Password;
use b8\Form\Element\Submit;
use Octo\System\Model\Contact;
use Octo\System\Store\ContactStore;

class Member
{
    protected static $instance;

    /**
     * @var ContactStore
     */
    protected $store;

    /**
     * @var Contact
     */
    protected $active;

    /**
     * @return Member
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getSessionKey()
    {
        return Config::getInstance()->get('site.namespace', 'octo') . '_member_id';
    }

    protected function __construct()
    {
    }

    public function init()
    {
        $this->store = Store::get('Contact');

        if (!empty($_SESSION[$this->getSessionKey()])) {
            $member = $this->store->getById($_SESSION[$this->getSessionKey()]);

            if (!empty($member)) {
                $this->active = $member;
            }
        }
    }

    public function isLoggedIn()
    {
        if (empty($this->store)) {
            $this->init();
        }

        if (!empty($this->active)) {
            return true;
        }

        return false;
    }

    public function getActiveMember()
    {
        if (empty($this->store)) {
            $this->init();
        }

        return $this->active;
    }

    public function getLoginForm($returnUrl = null)
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction('/member/login');

        if (!is_null($returnUrl)) {
            $rtn = new Hidden();
            $rtn->setName('rtn');
            $rtn->setValue($returnUrl);
            $form->addField($rtn);
        }

        $form->addField(Email::create('email', 'Email Address', true));
        $form->addField(Password::create('password', 'Password', true));

        $submit = new Submit();
        $submit->setClass('button pull-right');
        $submit->setValue('Login');

        $form->addField($submit);

        return $form;
    }

    public function login(Contact $contact)
    {
        $_SESSION[self::getSessionKey()] = $contact->getId();
        $this->active = $contact;
        Event::trigger('memberLogin', $member);
    }
}