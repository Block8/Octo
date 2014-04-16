<?php

/**
 * Variant store for table: variant */

namespace Octo\Shop\Store;

use b8;
use b8\Database\Query;
use Octo;

/**
 * Variant Store
 */
class VariantStore extends Octo\Store
{
    use Base\VariantStoreBase;

    public function getAll()
    {
        $query = new Query($this->getNamespace('Variant') . '\Model\Variant');
        $query->select('*')->from('variant');

        return $query->execute()->fetchAll();
    }

    public function getVariantsNotUsedByProduct($productId)
    {
        $query = new Query($this->getNamespace('Variant') . '\Model\Variant');
        $query->select('*')->from('variant');
        $query->where('id NOT IN ( SELECT DISTINCT variant.id
FROM variant
LEFT JOIN item_variant ON variant.id = item_variant.variant_id
WHERE item_variant.item_id = :product_id )');
        $query->bind(':product_id', $productId);

        return $query->execute()->fetchAll();
    }
}
