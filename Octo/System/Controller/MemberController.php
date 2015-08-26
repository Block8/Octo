<?php

namespace Octo\System\Controller;

use b8\Http\Response\RedirectResponse;
use Octo\Controller;
use Octo\Event;
use Octo\Html\Template;
use Octo\Member;
use Octo\Store;

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
                $_SESSION[Member::getSessionKey()] = $member->getId();

                Event::trigger('memberLogin', $member);

                $this->response = new RedirectResponse($this->response);
                $this->response->setHeader('Location', $this->getParam('rtn', '/'));
                return $this->response;
            } else {
                Event::trigger('memberLoginFailed', $view);
                $view->errorMessage = 'Your email address or password were wrong.';
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
}