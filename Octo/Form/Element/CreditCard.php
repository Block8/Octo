<?php
namespace Octo\Form\Element;

use b8\Config;
use b8\Form\Input;

class CreditCard extends Input
{
    protected $name;
    protected $type;
    protected $cardNumber;
    protected $expiryDate;
    protected $cvc;

    protected function onPreRender(&$view)
    {
        /** @var \Octo\AssetManager $assets */
        $assets = Config::getInstance()->get('Octo.AssetManager');
        $assets->addExternalJs('//cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.3.2/jquery.payment.min.js');

        parent::onPreRender($view);
    }

    public function getValue()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'number' => $this->cardNumber,
            'expiry' => $this->expiryDate,
            'cvc' => $this->cvc,
        ];
    }

    public function setValue(array $info)
    {
        $this->name = isset($info['name']) ? $info['name'] : null;
        $this->type = isset($info['type']) ? $info['type'] : null;
        $this->cardNumber = isset($info['number']) ? $info['number'] : null;
        $this->expiryDate = isset($info['expiry']) ? $info['expiry'] : null;
        $this->cvc = isset($info['cvc']) ? $info['cvc'] : null;
    }

    public function setCardNumber($number)
    {
        $this->cardNumber = $number;
    }

    public function setExpiry($date)
    {
        $this->expiryDate = $date;
    }

    public function setCvc($cvc)
    {
        $this->cvc = $cvc;
    }
}
