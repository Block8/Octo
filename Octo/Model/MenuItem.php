<?php

/**
 * MenuItem model for table: menu_item
 */

namespace Octo\Model;

use Octo\Model\Base\MenuItemBase;

/**
 * MenuItem Model
 * @uses Octo\Model\Base\MenuItemBase
 */
class MenuItem extends MenuItemBase
{
    /**
     * Get the path to the URL
     *
     * @return string
     * @author James Inman
     */
    public function getUrl()
    {
        $page = $this->getPage();
        if (isset($page)) {
            return $page->getUri();
        } else {
            return $this->data['url'];
        }
    }
}
