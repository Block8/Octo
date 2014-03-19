<?php

/**
 * Category model for table: category
 */

namespace Octo\Categories\Model;

use Octo;

/**
 * Category Model
 * @uses Octo\Categories\Model\Base\CategoryBaseBase
 */
class Category extends Octo\Model
{
    use Base\CategoryBase;

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);

        $this->getters['has_children'] = 'getHasChildren';
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
}
