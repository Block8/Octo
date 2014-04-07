<?php

/**
 * LineItem store for table: line_item */

namespace Octo\Invoicing\Store;

use b8\Database;
use Octo;

/**
 * LineItem Store
 */
class LineItemStore extends Octo\Store
{
    use Base\LineItemStoreBase;

    public function clearItemsForInvoice($invoice)
    {
        $query = 'DELETE FROM line_item WHERE invoice_id = :invoice_id';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':invoice_id', $invoice->getId());

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
