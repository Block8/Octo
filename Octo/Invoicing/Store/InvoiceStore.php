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

    public function getCompletedSinceDate(\DateTime $date)
    {
        $criteria = new Query\Criteria();
        $whereDate = new Query\Criteria();
        $whereDate->where('updated_date > :since');

        $whereStatus = new Query\Criteria();
        $whereStatus->where('invoice_status_id = ' . Octo\Invoicing\Model\Invoice::STATUS_PAID);

        $criteria->add($whereDate);
        $criteria->add($whereStatus);

        $query = new Query($this->getNamespace('Invoice') . '\Model\Invoice');
        $query->select('*')->from('invoice')->where($criteria)->order('id', 'DESC');
        $query->bind(':since', $date->format('Y-m-d'));

        return $query->execute()->fetchAll();
    }
}
