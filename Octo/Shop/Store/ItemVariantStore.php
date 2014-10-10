<?php

/**
 * ItemVariant store for table: item_variant */

namespace Octo\Shop\Store;

use Octo;
use b8\Database;
use b8\Database\Query;

/**
 * ItemVariant Store
 */
class ItemVariantStore extends Octo\Store
{
    use Base\ItemVariantStoreBase;

    // This class has been left blank so that you can modify it - changes in this file will not be overwritten.

    public function getAllForItem($itemId)
    {
        $query = new Query($this->getNamespace('ItemVariant') . '\Model\ItemVariant');
        $query->from('item_variant')->where('item_id = :item_id');
        $query->bind(':item_id', $itemId);

        try {
            $query->execute();
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemFile by ItemId and FileId', 0, $ex);
        }
    }

    public function deleteVariantForItem($itemId, $variantId)
    {
        $stmt = Database::getConnection('write')->prepare(
            'DELETE FROM item_variant WHERE item_id = :itemId AND variant_id = :variantId'
        );
        $stmt->bindValue(':variantId', $variantId);
        $stmt->bindValue(':itemId', $itemId);
        if ($stmt->execute()) {
            return $stmt->rowCount();
        } else {
            return 0;
        }
    }


}
