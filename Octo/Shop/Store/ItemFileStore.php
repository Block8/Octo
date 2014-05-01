<?php

/**
 * ItemFile store for table: item_file */

namespace Octo\Shop\Store;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Exception\StoreException;
use Octo;

/**
 * ItemFile Store
 */
class ItemFileStore extends Octo\Store
{
    use Base\ItemFileStoreBase;

    public function getByItemIdAndFileId($itemId, $fileId)
    {
        $query = new Query($this->getNamespace('ItemFile') . '\Model\ItemFile');
        $query->from('item_file')->where('file_id = :file_id AND item_id = :item_id');
        $query->bind(':file_id', $fileId);
        $query->bind(':item_id', $itemId);

        try {
            $query->execute();
            return $query->fetch(\PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemFile by ItemId and FileId', 0, $ex);
        }
    }
}
