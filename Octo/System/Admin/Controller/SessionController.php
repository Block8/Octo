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

        $this->view->emailFieldLabel = 'Email Address';
        $this->userStoreName = 'User';
        $this->userGetMethod = 'getByEmail';

        Event::trigger('beforeLogin', $this);

        if ($this->request->getMethod() == 'POST') {
            $ugMethod = $this->userGetMethod;
            $user = Store::get($this->userStoreName)->$ugMethod($this->getParam('email'));

            if ($user && password_verify($this->getParam('password', ''), $user->getHash())) {
                Event::trigger('loginSuccess', $user);
                $_SESSION['user_id'] = $user->getId();

                $url = '/' . $this->config->get('site.admin_uri');

                if (isset($_SESSION['previous_url'])) {
                    $url = $_SESSION['previous_url'];
                }
                
                header('Location: ' . $url);
                die;
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
        header('Location: /' . $this->config->get('site.admin_uri'));
        die;
    }

    public function reset()
    {

//        $recipients = array_filter(explode("\n", $form->getRecipients()));
//
//        $to = implode(', ', $recipients);
//
//        $subject = 'Form Submission: ' . $form->getTitle();
//        $headers = array(
//            'MIME-Version: 1.0',
//            'Content-type: text/html; charset=utf-8',
//        );
//
//        if ($submission->getContact() && $submission->getContact()->getEmail()) {
//            $headers[] = 'Reply-To: ' . $submission->getContact()->getEmail();
//        }
//
//        $config = Config::getInstance();
//
//        if (isset($config->site['email_from'])) {
//            $headers[] = 'From: ' . $config->site['email_from'];
//        }
//
//        $message         = Template::getPublicTemplate('Emails/FormSubmission');
//        $message->form   = $form;
//        $message->submission = $submission;
//
//        mail($to, $subject, $message->render(), implode("\r\n", $headers));
    }
}
