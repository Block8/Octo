<?php
namespace Octo\Admin\Controller;

use b8\Http\Response\RedirectResponse;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Model\Form;
use Octo\Store;

/**
 * Class MenuController
 */
class MenuController extends Controller
{
    /**
     * @var \Octo\Store\MenuStore
     */
    protected $menuStore;
    /**
     * @var \Octo\Store\MenuItemStore
     */
    protected $menuItemStore;

    public static function registerMenus(Menu $menu)
    {
        $item = $menu->addRoot('Menus', '/menu')->setIcon('list-ul');
        $item->addChild(new Menu\Item('Add Menu', '/menu/add'));
        $manage = new Menu\Item('Manage Menus', '/menu');
        $manage->addChild(new Menu\Item('Edit Menu', '/menu/edit', true));
        $manage->addChild(new Menu\Item('Delete Menu', '/menu/delete', true));
        $item->addChild($manage);
    }

    /**
     * Setup initial menu
     *
     * @return void
     */
    public function init()
    {
        $this->formStore = Store::get('Form');
        $this->submissionStore = Store::get('Submission');
        $this->contactStore = Store::get('Contact');

        $this->setTitle('Menus');
        $this->addBreadcrumb('Menus', '/menu');
    }

    public function index()
    {
        $forms = $this->menuStore->getAll();
        $this->view->menus = $menus;
    }

    public function add()
    {
        $this->addBreadcrumb('Add Menu', '/menu/add');
        if ($this->request->getMethod() == 'POST') {
//            $form = new Form();
//            $form->setValues($this->getParams());
//            $this->formStore->save($form);
//
//            $this->successMessage('Form saved successfully!', true);
//
//            $this->response = new RedirectResponse();
//            $this->response->setHeader('Location', '/backoffice/form');
//            return;
        }
    }

    public function edit($id)
    {
        $form = $this->formStore->getById($id);

        $this->addBreadcrumb($form->getTitle(), '/form/edit/' . $form->getId());

        if ($this->request->getMethod() == 'POST') {
            $form->setValues($this->getParams());
            $this->formStore->save($form);

            $this->successMessage('Form saved successfully!', true);

            $this->response = new RedirectResponse();
            $this->response->setHeader('Location', '/backoffice/form');
            return;
        }

        $form = [
            'id' => $form->getId(),
            'title' => $form->getTitle(),
            'recipients' => $form->getRecipients(),
            'definition' => htmlspecialchars(json_encode($form->getDefinition())),
            'thankyou_message' => $form->getThankyouMessage(),
        ];

        $this->view->form = $form;
    }

    public function submissions($formId)
    {

        $form = $this->formStore->getById($formId);

        $this->addBreadcrumb($form->getTitle(), '/form/edit/' . $formId);
        $this->addBreadcrumb('Submissions', '/form/submissions/' . $formId);

        $submissions = $this->submissionStore->getAllForForm($form, 0, 500);
        $this->view->submissions = $submissions;
        $this->view->form = $form;
    }

    public function submission($submissionId)
    {
        $submission = $this->submissionStore->getById($submissionId);
        $form = $submission->getForm();

        $this->addBreadcrumb($form->getTitle(), '/form/edit/' . $form->getId());
        $this->addBreadcrumb('Submissions', '/form/submissions/' . $form->getId());

        $extra = [];

        if ($submission->getExtra()) {
            foreach ($submission->getExtra() as $key => $value) {
                $extra[] = $this->getExtra($form->getDefinition(), $key, $value);
            }
        }

        $this->view->submission = $submission;
        $this->view->extra = $extra;
    }

    protected function getExtra($definition, $key, $value)
    {
        foreach ($definition as $field) {
            if ($field['id'] == $key) {
                $rtn = ['id' => $key, 'label' => $field['label']];

                if (isset($field['options'][$value])) {
                    $rtn['value'] = $field['options'][$value];
                } else {
                    $rtn['value'] = $value;
                }

                return $rtn;
            }
        }

        return ['id' => $key, 'label' => $key, 'value' => $value];
    }

    public function delete($formId)
    {
        $form = $this->formStore->getById($formId);
        $this->successMessage($form->getTitle() . ' has been deleted.', true);

        $this->formStore->delete($form);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/backoffice/form');
    }

    public function blockContact($submissionId)
    {
        $submission = $this->submissionStore->getById($submissionId);
        $contact = $submission->getContact();
        $this->successMessage('Contact blocked!', true);

        $contact->setIsBlocked(1);
        $this->contactStore->save($contact);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/backoffice/form/submission/'.$submissionId);
    }

    public function unblockContact($submissionId)
    {
        $submission = $this->submissionStore->getById($submissionId);
        $contact = $submission->getContact();
        $this->successMessage('Contact unblocked!', true);

        $contact->setIsBlocked(0);
        $this->contactStore->save($contact);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/backoffice/form/submission/'.$submissionId);
    }
}
