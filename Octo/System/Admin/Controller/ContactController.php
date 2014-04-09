<?php
namespace Octo\System\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Menu;

class ContactController extends Controller
{
    /**
     * @var \Octo\System\Store\ContactStore
     */
    protected $contactStore;

    /**
     * Setup page
     *
     * @return void
     */
    public function init()
    {
        $this->setTitle('Contacts');
        $this->addBreadcrumb('Contacts', '/contact');

        $this->contactStore = Store::get('Contact');
    }

    public static function registerMenus(Menu $menu)
    {
        $thisMenu = $menu->addRoot('Contacts', '/contact', true);
        $thisMenu->addChild(new Menu\Item('Contact Autocomplete', '/contact/autocomplete', true));
        $thisMenu->addChild(new Menu\Item('Block Contact', '/contact/block', true));
        $thisMenu->addChild(new Menu\Item('Unblock Contact', '/contact/unblock', true));
    }

    public function autocomplete()
    {
        $contacts = Store::get('Contact')->search($this->getParam('q', ''));

        $rtn = ['results' => [], 'more' => false];

        foreach ($contacts as $contact) {
            $name = $contact->getFirstName() . ' ' . $contact->getLastName();

            if ($contact->getCompany()) {
                $name .= ' (' . $contact->getCompany() . ')';
            }

            $rtn['results'][] = ['id' => $contact->getId(), 'text' => $name];
        }

        die(json_encode($rtn));
    }

    public function block($contactId)
    {
        $contact = $this->contactStore->getById($contactId);
        $this->successMessage('Contact blocked!', true);

        $contact->setIsBlocked(1);
        $this->contactStore->save($contact);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', $_SERVER['HTTP_REFERER']);
    }

    public function unblock($contactId)
    {
        $contact = $this->contactStore->getById($contactId);
        $this->successMessage('Contact unblocked!', true);

        $contact->setIsBlocked(0);
        $this->contactStore->save($contact);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', $_SERVER['HTTP_REFERER']);
    }
}
