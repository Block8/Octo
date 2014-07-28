<?php

namespace Octo\Forms\Block;

use b8;
use b8\Config;
use b8\Form\Element\Button;
use b8\Form\Element\Select;
use Octo\Admin\Form as AdminForm;
use b8\Form\Element\Submit;
use Octo\Block;
use Octo\Form as FormElement;
use Octo\System\Model\Contact;
use Octo\Forms\Model\Form as FormModel;
use Octo\Forms\Model\Submission;
use Octo\Store;
use Octo\Template;
use Octo\Event;

class Form extends Block
{
    /**
     * @var Store\FormStore
     */
    protected $formStore;

    /**
     * @var Store\ContactStore
     */
    protected $contactStore;

    /**
     * @var Store\SubmissionStore
     */
    protected $submissionStore;

    /**
     * @var \Octo\Forms\Model\Form
     */
    protected $formModel;

    /**
     * @var \b8\Form
     */
    protected $form;

    public static function getInfo()
    {
        return [
            'title' => 'Forms',
            'icon' => 'edit',
            'editor' => ['\Octo\Forms\Block\Form', 'getEditorForm']
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new AdminForm();
        $form->setId('block_' . $item['id']);

        $store = Store::get('Form');
        $rtn = $store->getAll(0, 1000);

        $forms = [];
        foreach ($rtn as $frm) {
            $forms[$frm->getId()] = $frm->getTitle();
        }

        $formSelect = Select::create('id', 'Form');
        $formSelect->setId('block_form_form_' . $item['id']);
        $formSelect->setOptions($forms);
        $formSelect->setClass('select2');
        $form->addField($formSelect);

        $saveButton = new Button();
        $saveButton->setValue('Save ' . $item['name']);
        $saveButton->setClass('block-save btn btn-success');
        $form->addField($saveButton);

        if (isset($item['content']) && is_array($item['content'])) {
            $form->setValues($item['content']);
        }

        return $form;
    }

    protected function init()
    {
        $this->formStore = Store::get('Form');
        $this->contactStore = Store::get('Contact');
        $this->submissionStore = Store::get('Submission');
    }

    public function renderNow()
    {
        if (empty($this->content['id'])) {
            return;
        }

        $this->formModel = $this->formStore->getById($this->content['id']);
        $this->form = $this->createForm($this->formModel->getDefinition());

        if ($this->request->getMethod() == 'POST') {
            if ($this->processForm($this->formModel, $this->form)) {
                $this->view->thankyou = $this->formModel->getThankyouMessage();
                return;
            } else {
                $this->view->error = 'There was an error with your submission, please check for errors below.';
            }
        }

        $this->view->form = $this->form->render();
    }

    protected function createForm($definition)
    {
        $form = new FormElement();
        $form->setAction($this->request->getPath());
        $form->setMethod('POST');

        foreach ($definition as $field) {
            $type = str_replace('_', ' ', $field['type']);
            $type = ucwords($type);
            $type = str_replace(' ', '', $type);
            $class = FormElement::getFieldClass($type);

            if (!is_null($class)) {
                $thisField = new $class($field['id']);
                $thisField->setLabel($field['label']);
                $thisField->setRequired($field['required']);

                if (array_key_exists('options', $field) && is_array($field['options'])) {
                    $thisField->setOptions($field['options']);
                }

                $form->addField($thisField);
            } else {
                print 'Type not found:  ' . $type . '<br>';
            }
        }

        $form->addField(new Submit());

        return $form;
    }

    protected function processForm(FormModel $formModel, FormElement &$form)
    {
        $form->setValues($this->request->getParams());
        if (!$form->validate()) {
            return false;
        }

        try {
            $contactDetails = $this->getContactDetails();
            $contact = $this->contactStore->findContact($contactDetails);

            if (is_null($contact)) {
                $contact = new Contact();
            }

            if ($contact->getIsBlocked()) {
                return true;
            }

            $contact->setValues($contactDetails);
            $contact = $this->contactStore->save($contact);


            $submission = new Submission();
            $submission->setForm($formModel);
            $submission->setCreatedDate(new \DateTime());
            $submission->setContact($contact);
            $submission->setMessage($this->request->getParam('message', null));

            $extra = [];
            foreach ($this->request->getParams() as $key => $value) {
                if (!array_key_exists($key, $contactDetails) && $key != 'message') {
                    $extra[$key] = $value;
                }
            }

            $submission->setExtra($extra);
            $submission = $this->submissionStore->save($submission);
            $params = array('formModel'=>$formModel, 'submission'=>$submission);
            Event::trigger('formsSubmit', $params);
            $this->sendEmails($formModel, $submission);
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }

    protected function getContactDetails()
    {
        $contact = [
            'email' => $this->request->getParam('email', null),
            'phone' => $this->request->getParam('phone', null),
            'title' => $this->request->getParam('title', null),
            'gender' => $this->request->getParam('gender', null),
            'first_name' => $this->request->getParam('first_name', null),
            'last_name' => $this->request->getParam('last_name', null),
            'address' => $this->request->getParam('address', null),
            'postcode' => $this->request->getParam('postcode', null),
            'date_of_birth' => $this->request->getParam('date_of_birth', null),
            'company' => $this->request->getParam('company', null),
            'marketing_optin' => $this->request->getParam('marketing_optin', null),
        ];

        return array_filter($contact);
    }

    protected function sendEmails(FormModel $form, Submission $submission)
    {
        $recipients = array_filter(explode("\n", $form->getRecipients()));

        $sendTo = implode(', ', $recipients);

        $subject = 'Form Submission: ' . $form->getTitle();
        $headers = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
        );

        if ($submission->getContact() && $submission->getContact()->getEmail()) {
            $headers[] = 'Reply-To: ' . $submission->getContact()->getEmail();
        }

        $config = Config::getInstance();

        if (isset($config->site['email_from'])) {
            $headers[] = 'From: ' . $config->site['email_from'];
        }

        $message         = Template::getPublicTemplate('Emails/FormSubmission');
        $message->form   = $form;
        $message->submission = $submission;

        mail($sendTo, $subject, $message->render(), implode("\r\n", $headers));
    }
}
