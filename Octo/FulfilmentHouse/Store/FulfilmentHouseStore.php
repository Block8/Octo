<?php

/**
 * FulfilmentHouse store for table: fulfilment_house */

namespace Octo\FulfilmentHouse\Store;

use b8\Database;
use b8\Database\Query;
use Octo;

/**
 * FulfilmentHouse Store
 */
class FulfilmentHouseStore extends Octo\Store
{
    use Base\FulfilmentHouseStoreBase;

    public function getAll()
    {
        $query = new Query($this->getNamespace('FulfilmentHouse') . '\Model\FulfilmentHouse');
        $query->select('*')->from('fulfilment_house');

        return $query->execute()->fetchAll();
    }
}
