<?php
namespace Octo\System\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\System\Model\Contact;

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
        $thisMenu = $menu->addRoot('Contacts', '/contact')->setIcon('users');
        $thisMenu->addChild(new Menu\Item('Add Contact', '/contact/add'));
        $thisMenu->addChild(new Menu\Item('Manage Contacts', '/contact'));
        $thisMenu->addChild(new Menu\Item('Edit Contact', '/contact/edit', true));

        $thisMenu->addChild(new Menu\Item('Contact Autocomplete', '/contact/autocomplete', true));
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

    public function index()
    {
        $this->view->contacts = $this->contactStore->all();
    }

    public function add()
    {
        $this->setTitle('Add Contact', 'Contacts');
        $this->addBreadcrumb('Add', '/contact/add');

        if ($this->request->getMethod() == 'POST') {
            $contact = new Contact();
            $contact->setValues($this->getParams());
            $contact = $this->contactStore->save($contact);

            $this->response = new RedirectResponse();
            $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/contact/edit/'.$contact->getId());
            return;
        }

        $this->view->form = $this->contactForm();
    }

    public function edit($contactId)
    {
        $contact = $this->contactStore->getById($contactId);
        $this->setTitle('Edit Contact', 'Contacts');

        if ($this->request->getMethod() == 'POST') {
            $contact->setValues($this->getParams());
            $contact = $this->contactStore->save($contact);

            $this->response = new RedirectResponse();
            $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/contact/edit/'.$contact->getId());
            return;
        }

        $form = $this->contactForm();;
        $form->setValues($contact->getDataArray());
        $this->view->form = $form;
    }

    public function delete($contactId)
    {
        $contact = $this->contactStore->getById($contactId);

        if (!empty($contact)) {
            $this->contactStore->delete($contact);
        }

        $this->successMessage('Contact deleted.', true);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri') . '/contact');
    }


    protected function contactForm()
    {
        $form = new \Octo\Admin\Form();
        $form->addField(Form\Element\Text::create('first_name', 'First Name', true));
        $form->addField(Form\Element\Text::create('last_name', 'Last Name', true));
        $form->addField(Form\Element\Email::create('email', 'Email Address', true));
        $form->addField(Form\Element\Text::create('phone', 'Phone Number'));
        $form->addField(Form\Element\Text::create('company', 'Company'));
        $form->addField(Form\Element\Submit::create('submit', 'Save')->setValue('Save Contact'));
        return $form;
    }
}
