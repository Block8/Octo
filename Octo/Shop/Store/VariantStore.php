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
}
