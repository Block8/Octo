<?php

namespace Octo\System\Controller;

use b8\Form\Element\Email;
use b8\Form\Element\Hidden;
use b8\Form\Element\Password;
use b8\Form\Element\Submit;
use b8\Http\Response\RedirectResponse;
use Octo\Controller;
use Octo\Event;
use Octo\Form;
use Octo\Html\Template;
use Octo\Member;
use Octo\Store;
use Octo\System\Model\Contact;

class MemberController extends Controller
{
    public function login()
    {
        $view = Template::load('login', 'Member');
        $view->form = Member::getInstance()->getLoginForm($this->getParam('rtn', '/'));

        Event::trigger('beforeMemberLogin', $this);

        if ($this->request->getMethod() == 'POST') {
            $member = Store::get('Contact')->getByEmail($this->getParam('email'));

            if ($member && password_verify($this->getParam('password', ''), $member->getPasswordHash())) {
                Member::getInstance()->login($member);

                $this->response = new RedirectResponse($this->response);
                $this->response->setHeader('Location', $this->getParam('rtn', '/'));
                return $this->response;
            } else {
                Event::trigger('memberLoginFailed', $view);

                $message = 'Your email address or password were incorrect.';

                $view->errorMessage = $message;
            }
        }

        return $view->render();
    }

    public function logout()
    {
        unset($_SESSION[Member::getSessionKey()]);

        $this->response = new RedirectResponse($this->response);
        $this->response->setHeader('Location', $this->getParam('rtn', '/'));
        return $this->response;
    }


    public function forgotPassword()
    {
        $view = Template::load('forgot-password', 'Member');
        $view->text = '<strong>Please fill in your email address below and we\'ll send you a link to reset your password.</strong><br><br>';
        $view->form = $this->forgotPasswordForm();

        if ($this->request->getMethod() == 'POST') {
            $member = Store::get('Contact')->getByEmail($this->getParam('email'));

            if (is_null($member)) {
                $view->errorMessage = 'There is no account registered with that email address.';
            }

            if (!is_null($member)) {
                $key = $this->getResetKey($member);

                $email = Template::load('forgot-password-email', 'Member');
                $email->member = $member;
                $email->key = $key;

                $html = $email->render();

                $to = $member->getFirstName() . ' ' . $member->getLastName() . ' <' . $member->getEmail() . '>';
                $name = $this->config->get('site.name');
                $from = $this->config->get('site.email_from');
                @mail($to, 'Reset Your ' . $name . ' Password', $html, 'From: ' . $from . PHP_EOL . 'Content-Type: text/html');
                $view->form = null;
                $view->text = 'Thanks, we\'ve emailed you a link to reset your password.';
            }
        }

        return $view->render();
    }

    public function resetPassword($memberId)
    {
        $view = Template::load('forgot-password', 'Member');
        $member = Store::get('Contact')->getById($memberId);
        $key = $this->getParam('k', null);

        if (is_null($member) || $key != $this->getResetKey($member)) {
            $this->response->setResponseCode(401);
            $view->errorMessage = 'Invalid password reset request.';
            return $view->render();
        }

        $view->text = '<strong>Please enter a new password below.</strong><br><br>';
        $view->form = $this->resetPasswordForm($memberId, $key);

        if ($this->request->getMethod() == 'POST') {
            $member->setPasswordHash(password_hash($this->getParam('password'), PASSWORD_DEFAULT));
            Store::get('Contact')->save($member);
            Member::getInstance()->login($member);

            $this->response = new RedirectResponse();
            $this->response->setHeader('Location', $this->config->get('site.url'));
            return;
        }

        return $view->render();
    }

    protected function forgotPasswordForm()
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction('/member/forgot-password');
        $form->disableValidation();

        $form->addField(Email::create('email', 'Email Address', true));

        $submit = new Submit();
        $submit->setClass('button pull-right');
        $submit->setValue('Submit');

        $form->addField($submit);

        return $form;
    }

    protected function resetPasswordForm($memberId, $key)
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction('/member/reset-password/' . $memberId);
        $form->enableValidation();

        $form->addField(Hidden::create('k', 'Key', true));
        $form->addField(Password::create('password', 'Your New Password', true));

        $submit = new Submit();
        $submit->setClass('button pull-right');
        $submit->setValue('Reset and Login');

        $form->setValues(['k' => $key]);

        $form->addField($submit);

        return $form;
    }

    protected function getResetKey(Contact $member)
    {
        return md5(date('Ymd') . $member->getPasswordHash());
    }
}