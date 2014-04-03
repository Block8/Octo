<?php
namespace Octo\System\Admin\Controller;

use b8\Form;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Menu;

class ContactController extends Controller
{
    /**
     * Setup page
     *
     * @return void
     */
    public function init()
    {
        $this->setTitle('Contacts');
        $this->addBreadcrumb('Contacts', '/contact');
    }

    public static function registerMenus(Menu $menu)
    {
        $thisMenu = $menu->addRoot('Contacts', '/contact', true);
        $thisMenu->addChild(new Menu\Item('Contact Autocomplete', '/contact/autocomplete', true));
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
}
