<?php

/**
 * Invoice model for table: invoice */

namespace Octo\Invoicing\Model;

use Octo;

/**
 * Invoice Model
 */
class Invoice extends Octo\Model
{
    use Base\InvoiceBase;

    const STATUS_NEW = 1;
    const STATUS_SENT = 2;
    const STATUS_PAID = 3;
    const STATUS_WRITTEN_OFF = 4;
    const STATUS_REFUNDED = 5;
}
