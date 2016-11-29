<?php

namespace Octo\System\Admin\Controller;

use Octo\Admin\Controller;
use Octo\Event;
use Octo\Store;

/**
 * Session Controller - Handles user login / logout.
 */
class SessionController extends Controller
{
    /**
     * @var \Octo\System\Store\UserStore
     */
    protected $userStore;

    public function init()
    {
        $this->response->disableLayout();
        $this->userStore = Store::get('User');
    }

    /**
     * Handles user login (form and processing)
     */
    public function login()
    {
        if (file_exists(APP_PATH . 'public/assets/images/cms-logo.png')) {
            $this->view->siteLogo = true;
        }

        $_SESSION['auth'] = 'login';

        $this->view->emailFieldLabel = 'Email Address';
        $this->userStoreName = 'User';
        $this->userGetMethod = 'getByEmail';

        Event::trigger('beforeLogin', $this);

        if ($this->request->getMethod() == 'POST') {
            $ugMethod = $this->userGetMethod;
            $user = Store::get($this->userStoreName)->$ugMethod($this->getParam('email'));

            if ($user && password_verify($this->getParam('password', ''), $user->getHash())) {
                $_SESSION['user_id'] = $user->getId();

                $url = $this->config->get('site.full_admin_url');

                if (isset($_SESSION['previous_url'])) {
                    $url = $_SESSION['previous_url'];
                }

                return $this->redirect($url);
            } else {
                Event::trigger('loginFailed', $this->view);
                $label = strtolower($this->view->emailFieldLabel);
                $this->view->errorMessage = 'Your ' . $label . ' or password were wrong.';
            }
        }
    }

    /**
     * Handles user logout.
     */
    public function logout()
    {
        $_SESSION = array();
        session_destroy();

        return $this->redirect('/session/login?logout=1');
    }

    public function forgotPassword()
    {
        if ($this->getRequest()->getMethod() == 'POST') {
            $email = $this->getParam('email');
            $user = $this->userStore->getByEmail($email);

            if (is_null($user)) {
                $this->view->error = 'No user exists with that email address, please try again.';
                return;
            }

            $userId = $user->getId();
            $key = md5(date('Y-m-d') . $user->getHash());
            $name = $user->getName();
            $siteName = $this->config->get('site.name');
            $url = $this->config->get('site.full_admin_url');

            $message = <<<OUT
Dear {$name},

You have received this email because you, or someone else, has requested a password reset for {$siteName}.

If this was you, please click the following link to reset your password: {$url}/session/reset-password/{$userId}/{$key}

Otherwise, please ignore this email and no action will be taken.

Thank you,
{$siteName}
OUT;

            $mailTo = $name . ' <' . $user->getEmail() . '>';
            @mail($mailTo, $siteName . ' Password Reset', $message, 'From: ' . $this->config->get('site.email_from'));
            $this->view->emailed = true;
        }
    }

    public function resetPassword($userId, $key)
    {
        $user = $this->userStore->getById($userId);
        $userKey = md5(date('Y-m-d') . $user->getHash());

        if (empty($user) || $key != $userKey) {
            $this->view->error = 'Invalid password reset request.';
            return;
        }

        if ($this->request->getMethod() == 'POST') {
            $hash = password_hash($this->getParam('password'), PASSWORD_DEFAULT);
            $user->setHash($hash);
            $this->userStore->save($user);

            $_SESSION['user_id'] = $user->getId();

            return $this->redirect('/');
        }

        $this->view->userId = $userId;
        $this->view->key = $key;

        return;
    }
}
