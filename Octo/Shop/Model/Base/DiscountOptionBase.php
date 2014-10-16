<?php

/**
 * DiscountOption base model for table: discount_option
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * DiscountOption Base Model
 */
trait DiscountOptionBase
{
    protected function init()
    {
        $this->tableName = 'discount_option';
        $this->modelName = 'DiscountOption';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['discount_id'] = null;
        $this->getters['discount_id'] = 'getDiscountId';
        $this->setters['discount_id'] = 'setDiscountId';
        $this->data['amount_initial'] = null;
        $this->getters['amount_initial'] = 'getAmountInitial';
        $this->setters['amount_initial'] = 'setAmountInitial';
        $this->data['amount_final'] = null;
        $this->getters['amount_final'] = 'getAmountFinal';
        $this->setters['amount_final'] = 'setAmountFinal';
        $this->data['updated_at'] = null;
        $this->getters['updated_at'] = 'getUpdatedAt';
        $this->setters['updated_at'] = 'setUpdatedAt';

        // Foreign keys:
        $this->getters['Discount'] = 'getDiscount';
        $this->setters['Discount'] = 'setDiscount';
    }
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
    * Get the value of DiscountId / discount_id.
    *
    * @return int
    */
    public function getDiscountId()
    {
        $rtn = $this->data['discount_id'];

        return $rtn;
    }

    /**
    * Get the value of AmountInitial / amount_initial.
    *
    * @return int
    */
    public function getAmountInitial()
    {
        $rtn = $this->data['amount_initial'];

        return $rtn;
    }

    /**
    * Get the value of AmountFinal / amount_final.
    *
    * @return int
    */
    public function getAmountFinal()
    {
        $rtn = $this->data['amount_final'];

        return $rtn;
    }

    /**
    * Get the value of UpdatedAt / updated_at.
    *
    * @return string
    */
    public function getUpdatedAt()
    {
        $rtn = $this->data['updated_at'];

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
    * Set the value of DiscountId / discount_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setDiscountId($value)
    {
        $this->validateNotNull('DiscountId', $value);
        $this->validateInt('DiscountId', $value);

        if ($this->data['discount_id'] === $value) {
            return;
        }

        $this->data['discount_id'] = $value;
        $this->setModified('discount_id');
    }

    /**
    * Set the value of AmountInitial / amount_initial.
    *
    * Must not be null.
    * @param $value int
    */
    public function setAmountInitial($value)
    {
        $this->validateNotNull('AmountInitial', $value);
        $this->validateInt('AmountInitial', $value);

        if ($this->data['amount_initial'] === $value) {
            return;
        }

        $this->data['amount_initial'] = $value;
        $this->setModified('amount_initial');
    }

    /**
    * Set the value of AmountFinal / amount_final.
    *
    * Must not be null.
    * @param $value int
    */
    public function setAmountFinal($value)
    {
        $this->validateNotNull('AmountFinal', $value);
        $this->validateInt('AmountFinal', $value);

        if ($this->data['amount_final'] === $value) {
            return;
        }

        $this->data['amount_final'] = $value;
        $this->setModified('amount_final');
    }

    /**
    * Set the value of UpdatedAt / updated_at.
    *
    * Must not be null.
    * @param $value string
    */
    public function setUpdatedAt($value)
    {
        $this->validateNotNull('UpdatedAt', $value);
        $this->validateString('UpdatedAt', $value);

        if ($this->data['updated_at'] === $value) {
            return;
        }

        $this->data['updated_at'] = $value;
        $this->setModified('updated_at');
    }
    /**
    * Get the Discount model for this DiscountOption by Id.
    *
    * @uses \Octo\Shop\Store\DiscountStore::getById()
    * @uses \Octo\Shop\Model\Discount
    * @return \Octo\Shop\Model\Discount
    */
    public function getDiscount()
    {
        $key = $this->getDiscountId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Discount', 'Octo\Shop')->getById($key);
    }

    /**
    * Set Discount - Accepts an ID, an array representing a Discount or a Discount model.
    *
    * @param $value mixed
    */
    public function setDiscount($value)
    {
        // Is this an instance of Discount?
        if ($value instanceof \Octo\Shop\Model\Discount) {
            return $this->setDiscountObject($value);
        }

        // Is this an array representing a Discount item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setDiscountId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setDiscountId($value);
    }

    /**
    * Set Discount - Accepts a Discount model.
    *
    * @param $value \Octo\Shop\Model\Discount
    */
    public function setDiscountObject(\Octo\Shop\Model\Discount $value)
    {
        return $this->setDiscountId($value->getId());
    }
}
