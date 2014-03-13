<?php

/**
 * Submission base model for table: submission
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Submission Base Model
 */
class SubmissionBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'submission';

    /**
    * @var string
    */
    protected $modelName = 'Submission';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'form_id' => null,
        'created_date' => null,
        'contact_id' => null,
        'extra' => null,
        'message' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'form_id' => 'getFormId',
        'created_date' => 'getCreatedDate',
        'contact_id' => 'getContactId',
        'extra' => 'getExtra',
        'message' => 'getMessage',

        // Foreign key getters:
        'Form' => 'getForm',
        'Contact' => 'getContact',
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'form_id' => 'setFormId',
        'created_date' => 'setCreatedDate',
        'contact_id' => 'setContactId',
        'extra' => 'setExtra',
        'message' => 'setMessage',

        // Foreign key setters:
        'Form' => 'setForm',
        'Contact' => 'setContact',
    ];

    /**
    * @var array
    */
    public $columns = [
        'id' => [
            'type' => 'int',
            'length' => 11,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ],
        'form_id' => [
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ],
        'created_date' => [
            'type' => 'datetime',
            'default' => null,
        ],
        'contact_id' => [
            'type' => 'int',
            'length' => 10,
            'default' => null,
        ],
        'extra' => [
            'type' => 'mediumtext',
            'nullable' => true,
            'default' => null,
        ],
        'message' => [
            'type' => 'mediumtext',
            'nullable' => true,
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'form_id' => ['columns' => 'form_id'],
        'contact_id' => ['columns' => 'contact_id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
        'submission_ibfk_1' => [
            'local_col' => 'form_id',
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
            'table' => 'form',
            'col' => 'id'
        ],
        'submission_ibfk_2' => [
            'local_col' => 'contact_id',
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
            'table' => 'contact',
            'col' => 'id'
        ],
    ];

    /**
    * Get the value of Id / id.
    *
    * @return int
    */
    public function getId()
    {
        $rtn = $this->data['id'];

        return $rtn;
    }

    /**
    * Get the value of FormId / form_id.
    *
    * @return int
    */
    public function getFormId()
    {
        $rtn = $this->data['form_id'];

        return $rtn;
    }

    /**
    * Get the value of CreatedDate / created_date.
    *
    * @return \DateTime
    */
    public function getCreatedDate()
    {
        $rtn = $this->data['created_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of ContactId / contact_id.
    *
    * @return int
    */
    public function getContactId()
    {
        $rtn = $this->data['contact_id'];

        return $rtn;
    }

    /**
    * Get the value of Extra / extra.
    *
    * @return string
    */
    public function getExtra()
    {
        $rtn = $this->data['extra'];

        return $rtn;
    }

    /**
    * Get the value of Message / message.
    *
    * @return string
    */
    public function getMessage()
    {
        $rtn = $this->data['message'];

        return $rtn;
    }

    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setId($value)
    {
        $this->validateNotNull('Id', $value);
        $this->validateInt('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of FormId / form_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setFormId($value)
    {
        $this->validateNotNull('FormId', $value);
        $this->validateInt('FormId', $value);

        if ($this->data['form_id'] === $value) {
            return;
        }

        $this->data['form_id'] = $value;
        $this->setModified('form_id');
    }

    /**
    * Set the value of CreatedDate / created_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setCreatedDate($value)
    {
        $this->validateNotNull('CreatedDate', $value);
        $this->validateDate('CreatedDate', $value);

        if ($this->data['created_date'] === $value) {
            return;
        }

        $this->data['created_date'] = $value;
        $this->setModified('created_date');
    }

    /**
    * Set the value of ContactId / contact_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setContactId($value)
    {
        $this->validateNotNull('ContactId', $value);
        $this->validateInt('ContactId', $value);

        if ($this->data['contact_id'] === $value) {
            return;
        }

        $this->data['contact_id'] = $value;
        $this->setModified('contact_id');
    }

    /**
    * Set the value of Extra / extra.
    *
    * @param $value string
    */
    public function setExtra($value)
    {
        $this->validateString('Extra', $value);

        if ($this->data['extra'] === $value) {
            return;
        }

        $this->data['extra'] = $value;
        $this->setModified('extra');
    }

    /**
    * Set the value of Message / message.
    *
    * @param $value string
    */
    public function setMessage($value)
    {
        $this->validateString('Message', $value);

        if ($this->data['message'] === $value) {
            return;
        }

        $this->data['message'] = $value;
        $this->setModified('message');
    }

    /**
    * Get the Form model for this Submission by Id.
    *
    * @uses \Octo\Store\FormStore::getById()
    * @uses \Octo\Model\Form
    * @return \Octo\Model\Form
    */
    public function getForm()
    {
        $key = $this->getFormId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Form', 'Octo')->getById($key);
    }

    /**
    * Set Form - Accepts an ID, an array representing a Form or a Form model.
    *
    * @param $value mixed
    */
    public function setForm($value)
    {
        // Is this an instance of Form?
        if ($value instanceof \Octo\Model\Form) {
            return $this->setFormObject($value);
        }

        // Is this an array representing a Form item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setFormId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setFormId($value);
    }

    /**
    * Set Form - Accepts a Form model.
    *
    * @param $value \Octo\Model\Form
    */
    public function setFormObject(\Octo\Model\Form $value)
    {
        return $this->setFormId($value->getId());
    }
    /**
    * Get the Contact model for this Submission by Id.
    *
    * @uses \Octo\Store\ContactStore::getById()
    * @uses \Octo\Model\Contact
    * @return \Octo\Model\Contact
    */
    public function getContact()
    {
        $key = $this->getContactId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Contact', 'Octo')->getById($key);
    }

    /**
    * Set Contact - Accepts an ID, an array representing a Contact or a Contact model.
    *
    * @param $value mixed
    */
    public function setContact($value)
    {
        // Is this an instance of Contact?
        if ($value instanceof \Octo\Model\Contact) {
            return $this->setContactObject($value);
        }

        // Is this an array representing a Contact item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setContactId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setContactId($value);
    }

    /**
    * Set Contact - Accepts a Contact model.
    *
    * @param $value \Octo\Model\Contact
    */
    public function setContactObject(\Octo\Model\Contact $value)
    {
        return $this->setContactId($value->getId());
    }
}
