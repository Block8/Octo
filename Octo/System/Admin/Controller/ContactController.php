<?php
namespace Octo\System\Admin\Controller;

use b8\Config;
use b8\Form;
use Octo\Event;
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
        /** @var \Octo\AssetManager $assets */
        $assets = Config::getInstance()->get('Octo.AssetManager');
        $assets->addJs('System', 'contact');

        $thisMenu = $menu->addRoot('Contacts', '/contact')->setIcon('users');
        $thisMenu->addChild(new Menu\Item('Add Contact', '/contact/add'));
        $thisMenu->addChild(new Menu\Item('Contact Popup', '/contact/popup', true));
        $thisMenu->addChild(new Menu\Item('Manage Contacts', '/contact'));
        $thisMenu->addChild(new Menu\Item('Edit Contact', '/contact/edit', true));

        $thisMenu->addChild(new Menu\Item('Contact Autocomplete', '/contact/autocomplete', true));
        $thisMenu->addChild(new Menu\Item('Block Contact', '/contact/block', true));
        $thisMenu->addChild(new Menu\Item('Unblock Contact', '/contact/unblock', true));

        $thisMenu->addChild(new Menu\Item('Opt-In Contact', '/contact/opt-in', true));
        $thisMenu->addChild(new Menu\Item('Opt-Out Contact', '/contact/opt-out', true));
    }

    public function index()
    {
        $this->template->contacts = $this->contactStore->getList();
    }

    public function download()
    {
        $contacts = $this->contactStore
                         ->where('marketing_optin', 1)
                         ->and('is_blocked', 0)
                         ->get();


        $buffer = fopen('php://temp', 'r+');

        fputcsv($buffer, ['ID', 'Email', 'First Name', 'Last Name', 'Phone', 'Mobile', 'Company', 'Postcode', 'Address 1', 'Address 2', 'Town']);

        foreach ($contacts as $contact) {
            $thisContact = [
                'id' => $contact->getId(),
                'email' => $contact->getEmail(),
                'first_name' => $contact->getFirstName(),
                'last_name' => $contact->getLastName(),
                'phone' => $contact->getPhone(),
                'mobile' => $contact->getMobile(),
                'company' => $contact->getCompany(),
                'postcode' => $contact->getPostcode(),
            ];

            $thisContact = array_merge($thisContact, $contact->getAddress());
            fputcsv($buffer, $thisContact);
        }

        rewind($buffer);
        $csv = '';

        while ($line = fgets($buffer)) {
            $csv .= $line;
        }

        fclose($buffer);

        return $this->response->download('contacts.csv', $csv);
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

        return $this->json($rtn);
    }

    public function popup($contactId)
    {
        $this->view->contact = $this->contactStore->getById($contactId);
        $this->response->disableLayout();
    }

    public function block($contactId)
    {
        $contact = $this->contactStore->getById($contactId);

        $contact->setIsBlocked(1);
        $this->contactStore->save($contact);

        return $this->redirect($_SERVER['HTTP_REFERER'])->success('Contact blocked.');
    }

    public function unblock($contactId)
    {
        $contact = $this->contactStore->getById($contactId);

        $contact->setIsBlocked(0);
        $this->contactStore->save($contact);

        return $this->redirect($_SERVER['HTTP_REFERER'])->success('Contact unblocked.');
    }

    public function optIn($contactId)
    {
        $contact = $this->contactStore->getById($contactId);

        $contact->setMarketingOptin(1);
        $this->contactStore->save($contact);

        return $this->redirect($_SERVER['HTTP_REFERER'])->success('Contact opted-in.');
    }

    public function optOut($contactId)
    {
        $contact = $this->contactStore->getById($contactId);

        $contact->setMarketingOptin(0);
        $this->contactStore->save($contact);

        return $this->redirect($_SERVER['HTTP_REFERER'])->success('Contact opted-out.');
    }

    public function add()
    {
        $this->setTitle('Add Contact', 'Contacts');
        $this->addBreadcrumb('Add', '/contact/add');

        if ($this->request->getMethod() == 'POST') {
            $contact = new Contact();
            $contact->setAddress([]);
            $contact->setValues($this->getParams());
            $contact = $this->contactStore->save($contact);

            return $this->redirect('/contact/edit/'.$contact->getId());
        }

        $this->view->form = $this->contactForm();
    }

    public function edit($contactId)
    {
        $contact = $this->contactStore->getById($contactId);
        $this->setTitle('Edit Contact', 'Contacts');

        if ($this->request->getMethod() == 'POST') {
            $contact->setValues($this->getParams());
            $this->contactStore->save($contact);

            return $this->redirect('/contact')->success('Contact updated.');
        }

        $form = $this->contactForm();;
        $form->setValues($contact->toArray());
        $this->view->form = $form;
    }

    public function delete($contactId)
    {
        $contact = $this->contactStore->getById($contactId);

        if (!empty($contact)) {
            $this->contactStore->delete($contact);
        }

        return $this->redirect('/contact')->success('Contact deleted.');
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
