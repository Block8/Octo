<?php

/**
 * Invoice store for table: invoice */

namespace Octo\Invoicing\Store;

use b8\Database;
use b8\Database\Query;
use Octo;

/**
 * Invoice Store
 */
class InvoiceStore extends Octo\Store
{
    use Base\InvoiceStoreBase;

    public function getAll()
    {
        $query = new Query($this->getNamespace('Invoice') . '\Model\Invoice');
        $query->select('*')->from('invoice')->order('id', 'DESC');

        return $query->execute()->fetchAll();
    }
}