<?php

/**
 * Rsm2000Log base model for table: rsm2000_log
 */

namespace Octo\GatewayRSM2000\Model\Base;

use b8\Store\Factory;

/**
 * Rsm2000Log Base Model
 */
trait Rsm2000LogBase
{
    protected function init()
    {
        $this->tableName = 'rsm2000_log';
        $this->modelName = 'Rsm2000Log';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['invoice_id'] = null;
        $this->getters['invoice_id'] = 'getInvoiceId';
        $this->setters['invoice_id'] = 'setInvoiceId';
        $this->data['donation'] = null;
        $this->getters['donation'] = 'getDonation';
        $this->setters['donation'] = 'setDonation';
        $this->data['purchase'] = null;
        $this->getters['purchase'] = 'getPurchase';
        $this->setters['purchase'] = 'setPurchase';
        $this->data['raw_auth_message'] = null;
        $this->getters['raw_auth_message'] = 'getRawAuthMessage';
        $this->setters['raw_auth_message'] = 'setRawAuthMessage';
        $this->data['trans_time'] = null;
        $this->getters['trans_time'] = 'getTransTime';
        $this->setters['trans_time'] = 'setTransTime';
        $this->data['trans_id'] = null;
        $this->getters['trans_id'] = 'getTransId';
        $this->setters['trans_id'] = 'setTransId';
        $this->data['trans_status'] = null;
        $this->getters['trans_status'] = 'getTransStatus';
        $this->setters['trans_status'] = 'setTransStatus';
        $this->data['card_type'] = null;
        $this->getters['card_type'] = 'getCardType';
        $this->setters['card_type'] = 'setCardType';
        $this->data['base_status'] = null;
        $this->getters['base_status'] = 'getBaseStatus';
        $this->setters['base_status'] = 'setBaseStatus';

        // Foreign keys:
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
    * Get the value of InvoiceId / invoice_id.
    *
    * @return int
    */
    public function getInvoiceId()
    {
        $rtn = $this->data['invoice_id'];

        return $rtn;
    }

    /**
    * Get the value of Donation / donation.
    *
    * @return float
    */
    public function getDonation()
    {
        $rtn = $this->data['donation'];

        return $rtn;
    }

    /**
    * Get the value of Purchase / purchase.
    *
    * @return float
    */
    public function getPurchase()
    {
        $rtn = $this->data['purchase'];

        return $rtn;
    }

    /**
    * Get the value of RawAuthMessage / raw_auth_message.
    *
    * @return string
    */
    public function getRawAuthMessage()
    {
        $rtn = $this->data['raw_auth_message'];

        return $rtn;
    }

    /**
    * Get the value of TransTime / trans_time.
    *
    * @return int
    */
    public function getTransTime()
    {
        $rtn = $this->data['trans_time'];

        return $rtn;
    }

    /**
    * Get the value of TransId / trans_id.
    *
    * @return string
    */
    public function getTransId()
    {
        $rtn = $this->data['trans_id'];

        return $rtn;
    }

    /**
    * Get the value of TransStatus / trans_status.
    *
    * @return string
    */
    public function getTransStatus()
    {
        $rtn = $this->data['trans_status'];

        return $rtn;
    }

    /**
    * Get the value of CardType / card_type.
    *
    * @return string
    */
    public function getCardType()
    {
        $rtn = $this->data['card_type'];

        return $rtn;
    }

    /**
    * Get the value of BaseStatus / base_status.
    *
    * @return string
    */
    public function getBaseStatus()
    {
        $rtn = $this->data['base_status'];

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
    * Set the value of InvoiceId / invoice_id.
    *
    * @param $value int
    */
    public function setInvoiceId($value)
    {
        $this->validateInt('InvoiceId', $value);

        if ($this->data['invoice_id'] === $value) {
            return;
        }

        $this->data['invoice_id'] = $value;
        $this->setModified('invoice_id');
    }

    /**
    * Set the value of Donation / donation.
    *
    * @param $value float
    */
    public function setDonation($value)
    {
        $this->validateFloat('Donation', $value);

        if ($this->data['donation'] === $value) {
            return;
        }

        $this->data['donation'] = $value;
        $this->setModified('donation');
    }

    /**
    * Set the value of Purchase / purchase.
    *
    * @param $value float
    */
    public function setPurchase($value)
    {
        $this->validateFloat('Purchase', $value);

        if ($this->data['purchase'] === $value) {
            return;
        }

        $this->data['purchase'] = $value;
        $this->setModified('purchase');
    }

    /**
    * Set the value of RawAuthMessage / raw_auth_message.
    *
    * @param $value string
    */
    public function setRawAuthMessage($value)
    {
        $this->validateString('RawAuthMessage', $value);

        if ($this->data['raw_auth_message'] === $value) {
            return;
        }

        $this->data['raw_auth_message'] = $value;
        $this->setModified('raw_auth_message');
    }

    /**
    * Set the value of TransTime / trans_time.
    *
    * @param $value int
    */
    public function setTransTime($value)
    {
        $this->validateInt('TransTime', $value);

        if ($this->data['trans_time'] === $value) {
            return;
        }

        $this->data['trans_time'] = $value;
        $this->setModified('trans_time');
    }

    /**
    * Set the value of TransId / trans_id.
    *
    * @param $value string
    */
    public function setTransId($value)
    {
        $this->validateString('TransId', $value);

        if ($this->data['trans_id'] === $value) {
            return;
        }

        $this->data['trans_id'] = $value;
        $this->setModified('trans_id');
    }

    /**
    * Set the value of TransStatus / trans_status.
    *
    * @param $value string
    */
    public function setTransStatus($value)
    {
        $this->validateString('TransStatus', $value);

        if ($this->data['trans_status'] === $value) {
            return;
        }

        $this->data['trans_status'] = $value;
        $this->setModified('trans_status');
    }

    /**
    * Set the value of CardType / card_type.
    *
    * @param $value string
    */
    public function setCardType($value)
    {
        $this->validateString('CardType', $value);

        if ($this->data['card_type'] === $value) {
            return;
        }

        $this->data['card_type'] = $value;
        $this->setModified('card_type');
    }

    /**
    * Set the value of BaseStatus / base_status.
    *
    * @param $value string
    */
    public function setBaseStatus($value)
    {
        $this->validateString('BaseStatus', $value);

        if ($this->data['base_status'] === $value) {
            return;
        }

        $this->data['base_status'] = $value;
        $this->setModified('base_status');
    }
}
