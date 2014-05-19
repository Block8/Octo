<?php

/**
 * LineItem model for table: line_item */

namespace Octo\Invoicing\Model;

use Octo;

/**
 * LineItem Model
 */
class LineItem extends Octo\Model
{
    use Base\LineItemBase;

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);

        $this->getters['line_price_with_tax'] = 'getLinePriceWithTax';
        $this->getters['item_price_with_tax'] = 'getItemPriceWithTax';
    }
}
