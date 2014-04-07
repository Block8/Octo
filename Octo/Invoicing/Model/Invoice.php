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
    const STATUS_DELETED = 3;
    const STATUS_PAID = 4;
    const STATUS_WRITTEN_OFF = 5;
    const STATUS_REFUNDED = 6;

    // This class has been left blank so that you can modify it - changes in this file will not be overwritten.
}
