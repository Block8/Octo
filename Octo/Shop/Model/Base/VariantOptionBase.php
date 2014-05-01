<?php

/**
 * VariantOption base model for table: variant_option
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * VariantOption Base Model
 */
trait VariantOptionBase
{
    protected function init()
    {
        $this->tableName = 'variant_option';
        $this->modelName = 'VariantOption';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['variant_id'] = null;
        $this->getters['variant_id'] = 'getVariantId';
        $this->setters['variant_id'] = 'setVariantId';
        $this->data['option_title'] = null;
        $this->getters['option_title'] = 'getOptionTitle';
        $this->setters['option_title'] = 'setOptionTitle';
        $this->data['position'] = null;
        $this->getters['position'] = 'getPosition';
        $this->setters['position'] = 'setPosition';

        // Foreign keys:
        $this->getters['Variant'] = 'getVariant';
        $this->setters['Variant'] = 'setVariant';
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
    * Get the value of VariantId / variant_id.
    *
    * @return int
    */
    public function getVariantId()
    {
        $rtn = $this->data['variant_id'];

        return $rtn;
    }

    /**
    * Get the value of OptionTitle / option_title.
    *
    * @return string
    */
    public function getOptionTitle()
    {
        $rtn = $this->data['option_title'];

        return $rtn;
    }

    /**
    * Get the value of Position / position.
    *
    * @return int
    */
    public function getPosition()
    {
        $rtn = $this->data['position'];

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
    * Set the value of VariantId / variant_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setVariantId($value)
    {
        $this->validateNotNull('VariantId', $value);
        $this->validateInt('VariantId', $value);

        if ($this->data['variant_id'] === $value) {
            return;
        }

        $this->data['variant_id'] = $value;
        $this->setModified('variant_id');
    }

    /**
    * Set the value of OptionTitle / option_title.
    *
    * Must not be null.
    * @param $value string
    */
    public function setOptionTitle($value)
    {
        $this->validateNotNull('OptionTitle', $value);
        $this->validateString('OptionTitle', $value);

        if ($this->data['option_title'] === $value) {
            return;
        }

        $this->data['option_title'] = $value;
        $this->setModified('option_title');
    }

    /**
    * Set the value of Position / position.
    *
    * @param $value int
    */
    public function setPosition($value)
    {
        $this->validateInt('Position', $value);

        if ($this->data['position'] === $value) {
            return;
        }

        $this->data['position'] = $value;
        $this->setModified('position');
    }
    /**
    * Get the Variant model for this VariantOption by Id.
    *
    * @uses \Octo\Shop\Store\VariantStore::getById()
    * @uses \Octo\Shop\Model\Variant
    * @return \Octo\Shop\Model\Variant
    */
    public function getVariant()
    {
        $key = $this->getVariantId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Variant', 'Octo\Shop')->getById($key);
    }

    /**
    * Set Variant - Accepts an ID, an array representing a Variant or a Variant model.
    *
    * @param $value mixed
    */
    public function setVariant($value)
    {
        // Is this an instance of Variant?
        if ($value instanceof \Octo\Shop\Model\Variant) {
            return $this->setVariantObject($value);
        }

        // Is this an array representing a Variant item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setVariantId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setVariantId($value);
    }

    /**
    * Set Variant - Accepts a Variant model.
    *
    * @param $value \Octo\Shop\Model\Variant
    */
    public function setVariantObject(\Octo\Shop\Model\Variant $value)
    {
        return $this->setVariantId($value->getId());
    }
}
