<?php

/**
 * Rsm2000Log store for table: rsm2000_log */

namespace Octo\GatewayRSM2000\Store;

use b8\Database;
use b8\Database\Query;
use Octo;

/**
 * Rsm2000Log Store
 */
class Rsm2000LogStore extends Octo\Store
{
    use Base\Rsm2000LogStoreBase;

    public function getAll()
    {
        //TODO: Why getNamespace does not work?
        $query = new Query('Octo\GatewayRSM2000\Model\Rsm2000Log');
        $query->select('*')->from('rsm2000_log');
        $query->order('id', 'DESC');

        return $query->execute()->fetchAll();
    }
}
