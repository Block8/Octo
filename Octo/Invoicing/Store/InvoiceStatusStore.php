<?php

/**
 * InvoiceStatus store for table: invoice_status */

namespace Octo\Invoicing\Store;

use b8\Database;
use b8\Database\Query;
use Octo;

/**
 * InvoiceStatus Store
 */
class InvoiceStatusStore extends Octo\Store
{
    use Base\InvoiceStatusStoreBase;

    public function getAll()
    {
        $query = new Query($this->getNamespace('InvoiceStatus') . '\Model\InvoiceStatus');
        $query->select('*')->from('invoice_status')->order('id', 'ASC');

        return $query->execute()->fetchAll();
    }
}
