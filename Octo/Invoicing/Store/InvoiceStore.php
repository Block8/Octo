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

    public function getAll($exclude = null)
    {
        $query = new Query($this->getNamespace('Invoice') . '\Model\Invoice');
        $query->select('*')->from('invoice');

        if(!empty($exclude)) {
            $query->where('invoice_status_id <> ' . (int)$exclude);
        }

        $query->order('id', 'DESC');

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

    public function getByUuid($invoiceUuid, $useConnection = 'read')
    {
        if (is_null($invoiceUuid)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Invoice').'\Model\Invoice', $useConnection);
        $query->select('*')->from('invoice')->limit(1);
        $query->where('`uuid` = :uuid');
        $query->bind(':uuid', $invoiceUuid);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Invoice by Uuid', 0, $ex);
        }
    }

    public function getInvoiceAlreadyCreated($invoiceTitle, Octo\System\Model\Contact $contact, $useConnection = 'read')
    {
        if (is_null($invoiceTitle) || is_null($contact)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Invoice').'\Model\Invoice', $useConnection);
        $query->select('*')->from('invoice')->limit(1);
        $query->where('`title` = :title AND contact_id = :contact_id AND invoice_status_id=1 AND total_paid IS NULL');
        $query->bind(':title', $invoiceTitle);
        $query->bind(':contact_id', $contact->getId());

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Invoice by Uuid', 0, $ex);
        }
    }



}
