<?php

/**
 * Category model for table: category
 */

namespace Octo\Categories\Model;

use Octo;
use Octo\Store;
/**
 * Category Model
 * @uses Octo\Categories\Model\Base\CategoryBase
 */
class Category extends Octo\Model
{
    use Base\CategoryBase;

    protected $categoryStore;

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);

        $this->getters['has_children'] = 'getHasChildren';
        $this->getters['children'] = 'getChildren';
    }

    /**
     * Get the absolute path to the image
     *
     * @return string
     * @author James Inman
     */
    public function getHasChildren()
    {
        if (isset($this->data['has_children'])) {
            return $this->data['has_children'];
        }
    }

    public function getChildren()
    {
        $this->categoryStore = Store::get('Category');
        return $this->categoryStore->getAllForParent($this->getId());
    }

}
