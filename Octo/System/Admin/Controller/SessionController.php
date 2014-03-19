<?php

namespace Octo\System\Admin\Controller;

use Octo\Admin\Controller;
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
        if ($this->request->getMethod() == 'POST') {
            $user = $this->userStore->getByEmail($this->getParam('email'));

            if ($user && password_verify($this->getParam('password', ''), $user->getHash())) {
                $_SESSION['user_id'] = $user->getId();

                $url = '/'.$this->config->get('site.admin_uri');

                if (isset($_SESSION['previous_url'])) {
                    $url = $_SESSION['previous_url'];
                }

                header('Location: ' . $url);
                die;
            } else {
                $this->view->errorMessage = 'Your email address or password were wrong.';
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
        header('Location: /' . $this->config->get('site.admin_uri'));
        die;
    }
}
