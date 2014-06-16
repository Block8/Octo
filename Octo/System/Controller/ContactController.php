<?php

namespace Octo\System\Controller;

use Octo\Block;
use Octo\Controller;
use Octo\Store;
use Octo\Template;
use b8\Http\Response\RedirectResponse;

class ContactController extends Controller
{
    /**
     * @var \Octo\System\Store\ContactStore
     */
    protected $contactStore;

    public function init()
    {

        $this->contactStore = Store::get('Contact');
    }

    public function unsubscribe()
    {
        $message = 'There was an error unsubscribing you.';
        $confirmLink = null;

        $unsubscriberEmail = $this->validateUnsubscriberEmail($this->getParam('email'));
        $unsubscriberHash  = $this->getParam('hash', '');

        if ($unsubscriberEmail && $unsubscriberHash && $this->getParam('confirmed'))
        {
            $unsubscriber['email'] = $unsubscriberEmail;

            $contact = $this->contactStore->findContact($unsubscriber);

            if ($unsubscriberHash === $this->contactStore->getUnsubscribeHash($contact->getId(), $unsubscriberEmail))
            {
                $contact->setMarketingOptin(0);
                $this->contactStore->saveByUpdate($contact);

                $message = 'Good-bye! You’ve successfully unsubscribed yourself from the email list.';
                //$data = ['model' => $contact, 'hash' => $unsubscriberHash];
                //Event::trigger('ContactUnsubscribed', $data);
            }
        } elseif ($unsubscriberEmail && $unsubscriberHash && !$this->getParam('confirmed')) {
            //Show confirmation
            $message = null;
            $confirmLink = '/contact/unsubscribe?email=' . $unsubscriberEmail . '&hash=' . $unsubscriberHash . '&confirmed=1';
        } else {
            $this->response = new RedirectResponse();
            $redirect = $this->config->get('site.url');
            $this->response->setHeader('Location', $redirect);
            return;
        }

        $view = Template::getPublicTemplate('Mailshot/unsubscribe');
        $view->message = $message;
        $view->confirmLink = $confirmLink;

        return $view->render();
    }

    private function validateUnsubscriberEmail($unsubscriberEmail)
    {
        if (empty($unsubscriberEmail))
        {
            return false;
        }

        $unsubscriberEmail = filter_var($unsubscriberEmail, FILTER_SANITIZE_EMAIL);

        if (!filter_var($unsubscriberEmail, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }

        return $unsubscriberEmail;
    }



}
